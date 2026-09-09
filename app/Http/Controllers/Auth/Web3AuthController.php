<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use App\User;
use App\UserDetails;
use App\AssetDetail;
use App\AccountDeposit;
use App\ProfileStore;
use Elliptic\EC;
use kornrunner\Keccak;

class Web3AuthController extends Controller
{
    /**
     * Check if a BEP-20 wallet address is already registered (Read-only, NO session creation)
     */
    public function checkWallet(Request $request)
    {
        $request->validate([
            'address' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/']
        ]);

        $walletAddress = strtolower($request->address);

        $asset = AssetDetail::whereRaw('LOWER(usdtbep20addr) = ?', [$walletAddress])
            ->orWhereRaw('LOWER(bep20addr) = ?', [$walletAddress])
            ->first();

        $isRegistered = false;
        if ($asset) {
            $userDetail = UserDetails::where('id', $asset->userid)->orWhere('userid', $asset->userid)->first();
            if ($userDetail) {
                $user = User::find($userDetail->userid);
                if ($user) {
                    $isRegistered = true;
                }
            }
        }

        if (!$isRegistered) {
            $user = User::whereRaw('LOWER(email) = ?', [$walletAddress])
                ->orWhereRaw('LOWER(uuid) = ?', [$walletAddress])
                ->orWhereRaw('LOWER(email) LIKE ?', [$walletAddress . '%'])
                ->first();
            if ($user) {
                $isRegistered = true;
            }
        }

        return response()->json([
            'status' => 'success',
            'is_registered' => $isRegistered,
            'address' => $request->address
        ]);
    }

    /**
     * Generate unique session challenge nonce for Web3 signature verification
     */
    public function getNonce(Request $request)
    {
        $request->validate([
            'address' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/']
        ]);

        $nonce = bin2hex(random_bytes(16));
        $timestamp = time();
        $address = strtolower($request->address);

        Session::put('web3_auth_nonce', $nonce);
        Session::put('web3_auth_address', $address);

        $message = "Welcome to Cyera AI!\n\nPlease sign this message to securely verify ownership of your wallet.\n\nWallet: {$address}\nNonce: {$nonce}\nTimestamp: {$timestamp}";

        return response()->json([
            'status' => 'success',
            'nonce' => $nonce,
            'message' => $message,
            'timestamp' => $timestamp
        ]);
    }

    /**
     * Cryptographic EIP-191 personal_sign signature verification
     */
    protected function verifySignature($message, $signature, $address)
    {
        try {
            $msgLen = strlen($message);
            $prefix = "\x19Ethereum Signed Message:\n{$msgLen}";
            $hashHex = Keccak::hash($prefix . $message, 256);

            $sig = substr($signature, 2); // remove 0x
            if (strlen($sig) !== 130) {
                return false;
            }

            $r = substr($sig, 0, 64);
            $s = substr($sig, 64, 64);
            $v = hexdec(substr($sig, 128, 2));

            if ($v >= 27) {
                $v -= 27;
            }

            $ec = new EC('secp256k1');
            $pubKey = $ec->recoverPubKey($hashHex, ['r' => $r, 's' => $s], $v);
            $pubHex = $pubKey->encode('hex');

            $pubKeccak = Keccak::hash(substr(hex2bin($pubHex), 1), 256);
            $recoveredAddress = '0x' . substr($pubKeccak, -40);

            return strtolower($recoveredAddress) === strtolower($address);
        } catch (\Exception $e) {
            \Log::error('Web3 Signature Recovery Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Authenticate Web3 wallet address with mandatory cryptographic signature check
     */
    public function web3Login(Request $request)
    {
        $request->validate([
            'address' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'signature' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{130}$/'],
            'message' => ['required', 'string'],
            'sponsor' => ['nullable', 'string']
        ]);

        $walletAddress = strtolower($request->address);

        // 1. Verify Nonce in Session to Prevent Replay Attacks
        $sessionNonce = Session::get('web3_auth_nonce');
        if (empty($sessionNonce) || strpos($request->message, $sessionNonce) === false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Security challenge expired or invalid. Please refresh and try again.'
            ], 401);
        }

        // 2. Cryptographic Signature Verification
        if (!$this->verifySignature($request->message, $request->signature, $walletAddress)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cryptographic signature verification failed! Access denied.'
            ], 401);
        }

        // Invalidate nonce after successful cryptographic verification
        Session::forget('web3_auth_nonce');

        // 3. Check if an asset_detail exists with this verified address
        $asset = AssetDetail::whereRaw('LOWER(usdtbep20addr) = ?', [$walletAddress])
            ->orWhereRaw('LOWER(bep20addr) = ?', [$walletAddress])
            ->first();

        if ($asset) {
            $userDetail = UserDetails::where('id', $asset->userid)->orWhere('userid', $asset->userid)->first();
            if ($userDetail) {
                $user = User::find($userDetail->userid);
                if ($user) {
                    return $this->loginAndRedirect($user, $request->address);
                }
            }
        }

        // 4. Check if users.email or users.usersname or users.uuid matches wallet address
        $user = User::whereRaw('LOWER(email) = ?', [$walletAddress])
            ->orWhereRaw('LOWER(uuid) = ?', [$walletAddress])
            ->orWhereRaw('LOWER(email) LIKE ?', [$walletAddress . '%'])
            ->first();

        if ($user) {
            return $this->loginAndRedirect($user, $request->address);
        }

        // 5. If NOT registered and sponsor is provided, auto-register
        if (!empty($request->sponsor)) {
            return $this->web3RegisterInternal($request);
        }

        // Wallet not registered yet & no sponsor in URL -> prompt user for sponsor ID
        return response()->json([
            'status' => 'not_registered',
            'message' => 'New wallet verified. Please enter sponsor ID to complete one-time registration.',
            'address' => $request->address
        ]);
    }

    /**
     * 1-Step Instant Web3 Registration under Sponsor with Cryptographic Signature Check
     */
    public function web3Register(Request $request)
    {
        $request->validate([
            'address' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
            'signature' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{130}$/'],
            'message' => ['required', 'string'],
            'sponsor' => ['nullable', 'string']
        ]);

        $walletAddress = strtolower($request->address);

        // Verify Nonce in Session
        $sessionNonce = Session::get('web3_auth_nonce');
        if (empty($sessionNonce) || strpos($request->message, $sessionNonce) === false) {
            return response()->json([
                'status' => 'error',
                'message' => 'Security challenge expired. Please refresh and reconnect wallet.'
            ], 401);
        }

        // Cryptographic Signature Verification
        if (!$this->verifySignature($request->message, $request->signature, $walletAddress)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cryptographic signature verification failed! Registration aborted.'
            ], 401);
        }

        Session::forget('web3_auth_nonce');

        return $this->web3RegisterInternal($request);
    }

    /**
     * Internal Registration Engine
     */
    protected function web3RegisterInternal(Request $request)
    {
        $walletAddress = strtolower($request->address);

        // Ensure wallet not already used
        $existingAsset = AssetDetail::whereRaw('LOWER(usdtbep20addr) = ?', [$walletAddress])
            ->orWhereRaw('LOWER(bep20addr) = ?', [$walletAddress])
            ->first();

        if ($existingAsset) {
            $userDetail = UserDetails::where('id', $existingAsset->userid)->orWhere('userid', $existingAsset->userid)->first();
            if ($userDetail) {
                $user = User::find($userDetail->userid);
                if ($user) {
                    return $this->loginAndRedirect($user, $request->address);
                }
            }
        }

        // Validate Sponsor
        $sponsorUser = null;
        if (!empty($request->sponsor)) {
            $sponsorUser = User::where('uuid', $request->sponsor)->orWhere('usersname', $request->sponsor)->first();
        }
        if (!$sponsorUser) {
            // Default to root admin
            $sponsorUser = User::where('licence', 3)->first() ?: User::first();
        }

        $sponsorDetail = UserDetails::where('userid', $sponsorUser->id)->first();
        $sponsorId = $sponsorDetail ? $sponsorDetail->id : 1;

        DB::beginTransaction();
        try {
            // Generate unique user UUID
            $uniqueCode = 'CY' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 7));
            while (User::where('uuid', $uniqueCode)->exists()) {
                $uniqueCode = 'CY' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 7));
            }

            // Create User
            $newUser = User::create([
                'uuid' => $uniqueCode,
                'usersname' => 'Cyera_' . substr($request->address, 2, 6),
                'email' => $request->address . '@cyera.ai',
                'password' => Hash::make($request->address . '_' . time()),
                's_password' => substr(md5($request->address), 0, 8),
                'permission' => 1,
                'status' => 1,
                'licence' => 0,
                'doj' => date('Y-m-d H:i:s'),
                'contact' => '',
                'ccode' => '+1'
            ]);

            // Create UserDetails
            $newDetail = UserDetails::create([
                'userid' => $newUser->id,
                'sponsorid' => $sponsorId,
                'userstate' => 0,
                'userstatus' => 0,
                'capping' => 0,
                'roi_status' => 1,
                'booster' => 0,
                'rank_id' => 0,
                'current_self_investment' => 0,
                'total_self_investment' => 0,
                'current_investment' => 0,
                'total_investment' => 0,
                'current_direct_investment' => 0,
                'total_direct_investment' => 0,
                'current_level_investment' => 0,
                'total_level_investment' => 0,
                'total_income' => 0,
                'current_income' => 0,
                'capping_limit' => 0,
                'cap_consumed' => 0,
                'cap_multiplier' => 2.00,
                'last_capping_update' => date('Y-m-d H:i:s'),
                'last_rank_update' => date('Y-m-d H:i:s'),
            ]);

            // Create AssetDetail
            AssetDetail::create([
                'userid' => $newDetail->id,
                'bep20addr' => $request->address,
                'usdtbep20addr' => $request->address,
                'usdttrc20addr' => '',
            ]);

            // Initialize AccountDeposit
            AccountDeposit::create([
                'userid' => $newDetail->id,
                'amount' => Crypt::encrypt(0),
            ]);

            DB::commit();

            return $this->loginAndRedirect($newUser, $request->address);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Registration failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Helper to update authenticated BEP20 address, start session, and return success response
     */
    private function loginAndRedirect($user, $walletAddress = null)
    {
        Auth::login($user);

        $userDetail = UserDetails::where('user_details.userid', $user->id)
            ->leftjoin('users', 'users.id', '=', 'user_details.userid')
            ->leftjoin('users as guider', 'guider.id', '=', 'user_details.sponsorid')
            ->select('user_details.id as id', 'users.usersname as name', 'users.uuid as userid', 'users.email as email', 'users.licence as licence', 'users.permission as permission', 'users.uuid as uuid', 'guider.uuid as sponsorid', 'users.doj')
            ->first();

        // Automatically update & sync the BEP20 wallet address for this authenticated user
        if ($walletAddress && $userDetail) {
            $assetDetail = AssetDetail::firstOrNew(['userid' => $userDetail->id]);
            $assetDetail->bep20addr = $walletAddress;
            $assetDetail->usdtbep20addr = $walletAddress;
            $assetDetail->updated_at = now();
            $assetDetail->save();

            // Also sync if an entry exists under user's id
            $assetUser = AssetDetail::where('userid', $user->id)->first();
            if ($assetUser && $assetUser->id != $assetDetail->id) {
                $assetUser->bep20addr = $walletAddress;
                $assetUser->usdtbep20addr = $walletAddress;
                $assetUser->updated_at = now();
                $assetUser->save();
            }

            $userDetail->walletaddress = $walletAddress;
            $userDetail->usdtbep20address = $walletAddress;
        }

        Session::put('user', $userDetail);
        Session::put('logtime', strtotime(now()));

        $redirectUrl = ($user->licence == '3' || $user->licence == '2' || $user->licence == '4')
            ? '/Main/DashboardToday'
            : '/User/Dashboard';

        return response()->json([
            'status' => 'success',
            'redirect' => $redirectUrl,
            'user' => [
                'name' => $user->usersname,
                'uuid' => $user->uuid,
                'address' => $walletAddress ?: ($user->assetDetail ? $user->assetDetail->usdtbep20addr : $user->email)
            ]
        ]);
    }
}

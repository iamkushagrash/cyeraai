<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\UserDetails;
use App\AssetDetail;
use App\AccountDeposit;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\SupportQueryController;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/register';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the application registration form with sponsor & wallet pre-fill support.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $userid
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    /**
     * Show the application registration form with sponsor & wallet pre-fill support.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $userid
     * @return \Illuminate\Http\Response|\Illuminate\View\View
     */
    public function showRegistrationForm(Request $request, $userid = null)
    {
        $ref = $userid ?: $request->query('ref', $request->query('sponsor', $request->query('referral', '')));
        $wallet = $request->query('wallet', '');

        return view('auth.register', [
            'userid' => $ref,
            'wallet' => $wallet
        ]);
    }

    /**
     * Helper to find a sponsor User by Wallet Address (BEP-20 / USDT-BEP20), UUID, or Email
     *
     * @param string $ref
     * @return \App\User|null
     */
    public static function resolveSponsorUser($ref)
    {
        $ref = trim($ref);
        if (empty($ref)) {
            return null;
        }

        $refLower = strtolower($ref);

        // 1. Check if ref is a BEP-20 wallet address (0x...)
        if (preg_match('/^0x[a-fA-F0-9]{40}$/', $ref)) {
            $asset = AssetDetail::whereRaw('LOWER(usdtbep20addr) = ?', [$refLower])
                ->orWhereRaw('LOWER(bep20addr) = ?', [$refLower])
                ->first();

            if ($asset) {
                $uDetail = UserDetails::where('id', $asset->userid)->orWhere('userid', $asset->userid)->first();
                if ($uDetail) {
                    $u = User::find($uDetail->userid);
                    if ($u) return $u;
                }
            }
        }

        // 2. Lookup by UUID or Email
        $user = User::where('uuid', $ref)
            ->orWhereRaw('LOWER(uuid) = ?', [$refLower])
            ->orWhere('email', $ref)
            ->orWhereRaw('LOWER(email) = ?', [$refLower])
            ->first();

        if ($user) {
            return $user;
        }

        // 3. Fallback: check asset_details if ref without 0x or any matching address
        $asset = AssetDetail::whereRaw('LOWER(usdtbep20addr) = ?', [$refLower])
            ->orWhereRaw('LOWER(bep20addr) = ?', [$refLower])
            ->first();

        if ($asset) {
            $uDetail = UserDetails::where('id', $asset->userid)->orWhere('userid', $asset->userid)->first();
            if ($uDetail) {
                return User::find($uDetail->userid);
            }
        }

        return null;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'           => ['nullable', 'string', 'max:255'],
            'email'          => ['nullable', 'string', 'email', 'max:255', 'unique:users,email'],
            'referrer'       => ['required', 'string'],
            'contact'        => ['nullable'],
            'countrycode'    => ['nullable', 'string'],
            'wallet_address' => ['required', 'string', 'regex:/^0x[a-fA-F0-9]{40}$/'],
        ], [
            'referrer.required'       => 'Sponsor Wallet Address is required for registration.',
            'wallet_address.required' => 'Web3 Wallet connection is required to register.',
            'wallet_address.regex'    => 'Invalid BEP-20 Web3 wallet address format.',
            'email.unique'            => 'This email address is already registered.',
        ])->after(function ($validator) use ($data) {
            // Check Sponsor validity (Wallet address or UUID)
            if (!empty($data['referrer'])) {
                $sponsorUser = self::resolveSponsorUser($data['referrer']);
                if (is_null($sponsorUser)) {
                    $validator->errors()->add('referrer', 'Invalid Sponsor Wallet Address / ID. Please verify and enter a valid Sponsor.');
                }
            }

            // Check if wallet address is already registered & check 50 USDT balance
            if (!empty($data['wallet_address'])) {
                $walletLower = strtolower(trim($data['wallet_address']));
                $existingAsset = AssetDetail::whereRaw('LOWER(usdtbep20addr) = ?', [$walletLower])
                    ->orWhereRaw('LOWER(bep20addr) = ?', [$walletLower])
                    ->first();

                if ($existingAsset) {
                    $validator->errors()->add('wallet_address', 'This Web3 Wallet is already registered. Please proceed to Sign In.');
                } else {
                    $usdtBal = self::checkWalletUsdtBalance($walletLower);
                    if ($usdtBal < 50.00) {
                        $validator->errors()->add('wallet_address', 'Minimum $50 USDT balance is required in your connected Web3 wallet to register. Your current wallet balance is $' . number_format($usdtBal, 2) . ' USDT.');
                    }
                }
            }
        });
    }

    /**
     * Check USDT Balance on BSC Mainnet via RPC
     */
    public static function checkWalletUsdtBalance($walletAddress)
    {
        $isDemo = env('DEMO_MODE', false) || env('TEST_MODE', false) || config('app.demo_mode', false);
        if ($isDemo) {
            return 100.00; // Simulated $100 USDT balance in DEMO MODE
        }

        $usdtContract = strtolower(env('USDT_TOKEN_ADDRESS', '0x55d398326f99059fF775485246999027B3197955'));
        $cleanAddr = ltrim(strtolower($walletAddress), '0x');
        $cleanAddrPadded = str_pad($cleanAddr, 64, '0', STR_PAD_LEFT);
        $data = '0x70a08231' . $cleanAddrPadded;

        $rpcEndpoints = [
            "https://bsc.meowrpc.com",
            "https://bsc-dataseed1.defibit.io/",
            "https://bsc-dataseed.binance.org/",
            "https://1rpc.io/bnb"
        ];

        $payload = json_encode([
            'jsonrpc' => '2.0',
            'method'  => 'eth_call',
            'params'  => [
                [
                    'to'   => $usdtContract,
                    'data' => $data
                ],
                'latest'
            ],
            'id' => 1
        ]);

        foreach ($rpcEndpoints as $rpcUrl) {
            $ch = curl_init($rpcUrl);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type:application/json']);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);

            if ($response) {
                $json = json_decode($response, true);
                if (isset($json['result']) && !empty($json['result']) && $json['result'] !== '0x') {
                    $hexVal = ltrim(substr($json['result'], 2), '0');
                    if ($hexVal === '') return 0.00;

                    $dec = '0';
                    $len = strlen($hexVal);
                    for ($i = 0; $i < $len; $i++) {
                        $digit = hexdec($hexVal[$i]);
                        $dec = bcadd(bcmul($dec, '16'), (string)$digit);
                    }

                    return (float) bcdiv($dec, '1000000000000000000', 4);
                }
            }
        }

        return 0.00;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        set_time_limit(0);
        $guiderid = 0;
        if (!empty($data['referrer'])) {
            $guiderUser = self::resolveSponsorUser($data['referrer']);
            if ($guiderUser) {
                $guiderDetail = UserDetails::where('userid', $guiderUser->id)->first();
                $guiderid = $guiderDetail ? $guiderDetail->id : $guiderUser->id;
            }
        }

        $walletLower = !empty($data['wallet_address']) ? strtolower($data['wallet_address']) : '';
        $userEmail = !empty($data['email']) ? $data['email'] : ($walletLower ? $walletLower . '@cyera.ai' : 'user_' . rand(10000, 99999) . '@cyera.ai');
        $userContact = !empty($data['contact']) ? $data['contact'] : '';
        $defaultPassword = 'CY@' . rand(100000, 999999);
        $userPassword = !empty($data['password']) ? $data['password'] : $defaultPassword;

        // Auto-assign display name from wallet address if name not provided
        $walletAddr = !empty($data['wallet_address']) ? $data['wallet_address'] : '';
        $userName = !empty($data['name']) ? $data['name'] : (!empty($walletAddr) ? substr($walletAddr, 0, 6) . '...' . substr($walletAddr, -4) : 'Cyera Member');

        $randomId = $this->randomid();
        $user = User::create([
            'usersname'         => $userName,
            'email'             => $userEmail,
            'contact'           => $userContact,
            'ccode'             => !empty($data['countrycode']) ? $data['countrycode'] : '+91',
            'password'          => Hash::make($userPassword),
            's_password'        => Crypt::encrypt($userPassword),
            'doj'               => date("Y-m-d"),
            'created_at'        => now(),
            'uuid'              => $randomId,
            'email_verified_at' => now(),
        ]);

        $newDetail = UserDetails::create([
            'userid'                    => $user->id,
            'sponsorid'                 => $guiderid,
            'userstate'                 => 0,
            'userstatus'                => 0,
            'capping'                   => 0,
            'roi_status'                => 1,
            'booster'                   => 0,
            'rank_id'                   => 0,
            'current_self_investment'   => 0,
            'total_self_investment'     => 0,
            'current_investment'        => 0,
            'total_investment'          => 0,
            'current_direct_investment' => 0,
            'total_direct_investment'   => 0,
            'current_level_investment'  => 0,
            'total_level_investment'    => 0,
            'total_income'              => 0,
            'current_income'            => 0,
            'capping_limit'             => 0,
            'cap_consumed'              => 0,
            'cap_multiplier'            => 2.00,
            'last_capping_update'       => date('Y-m-d H:i:s'),
            'last_rank_update'          => date('Y-m-d H:i:s'),
        ]);

        // Auto-bind wallet address if present (from Web3 session or form)
        AssetDetail::create([
            'userid'        => $newDetail->id,
            'bep20addr'     => $walletAddr ?: '',
            'usdtbep20addr' => $walletAddr ?: '',
            'usdttrc20addr' => '',
        ]);

        AccountDeposit::create([
            'userid' => $newDetail->id,
            'amount' => Crypt::encrypt(0),
        ]);

        $details = [];
        $details['id'] = $user->email;
        $details['password'] = $userPassword;
        $details['uid'] = $newDetail->id;
        $details['email'] = $user->email;
        $details['contact'] = $userContact;
        $details['name'] = $userName;
        $details['referrerid'] = $data['referrer'];
        $details['uniqueid'] = $randomId;
        $details['view'] = 'welcomeMail';
        $details['subject'] = 'Welcome to Cyera AI.';

        event(new \App\Events\UserRegistered($details));

        if (!empty($data['email'])) {
            try {
                $mailObj = new SupportQueryController();
                $mailStatus = $mailObj->sendMailgun($details);
            } catch (\Exception $e) {
                \Log::info('Error in welcome mail after registration: ' . $e->getMessage());
            }
        }

        return $user;
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function registered(Request $request, $user)
    {
        $plainPassword = '';
        try {
            $plainPassword = Crypt::decrypt($user->s_password);
        } catch (\Exception $e) {
            $plainPassword = 'Saved Securely';
        }

        $sessionDetails = [
            'username' => $user->email,
            'password' => $plainPassword,
            'uniqueid' => $user->uuid,
            'wallet'   => $request->wallet_address
        ];

        return redirect('/register')->with('details', $sessionDetails)->with('success', 'Registration Successful.');
    }

    /**
     * Live AJAX Sponsor lookup by Wallet Address (0x...), UUID, or Email
     *
     * @param  string  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getSponsor($id)
    {
        $id = trim($id);
        if (strlen($id) < 4) {
            return response()->json([
                'status'  => 1,
                'message' => 'Sponsor input is too short'
            ]);
        }

        $user = self::resolveSponsorUser($id);

        if (is_null($user)) {
            return response()->json([
                'status'  => 1,
                'message' => 'Invalid Sponsor Wallet Address'
            ]);
        }

        // Get sponsor wallet address for clean verified display (NO name returned)
        $sponsorDetail = UserDetails::where('userid', $user->id)->first();
        $sponsorWallet = '';
        if ($sponsorDetail) {
            $asset = AssetDetail::where('userid', $sponsorDetail->id)->first();
            if ($asset && !empty($asset->usdtbep20addr)) {
                $sponsorWallet = $asset->usdtbep20addr;
            } elseif ($asset && !empty($asset->bep20addr)) {
                $sponsorWallet = $asset->bep20addr;
            }
        }

        $displayTag = !empty($sponsorWallet) 
            ? (substr($sponsorWallet, 0, 6) . '...' . substr($sponsorWallet, -4)) 
            : $user->uuid;

        return response()->json([
            'status'  => 0,
            'display' => $displayTag,
            'wallet'  => $sponsorWallet,
            'uuid'    => $user->uuid
        ]);
    }

    /**
     * Referral link redirector (supports wallet address or UUID)
     *
     * @param  string  $userid
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reffer($userid)
    {
        return redirect('/register?ref=' . $userid);
    }

    /**
     * Generate unique random CAI ID
     *
     * @return string
     */
    public function randomid()
    {
        $val = true;
        while ($val) {
            $num = "CAI" . rand(1111111, 9999999);
            $chkUser = User::where('uuid', $num)->first();
            if (is_null($chkUser)) {
                $val = false;
            }
        }
        return $num;
    }
}

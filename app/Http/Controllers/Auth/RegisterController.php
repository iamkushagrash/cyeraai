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
    public function showRegistrationForm(Request $request, $userid = null)
    {
        $ref = $userid ?: $request->query('ref', $request->query('sponsor', ''));
        $wallet = $request->query('wallet', '');

        return view('auth.register', [
            'userid' => $ref,
            'wallet' => $wallet
        ]);
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
            'referrer.required'       => 'Sponsor ID is required for registration.',
            'wallet_address.required' => 'Web3 Wallet connection is required to register.',
            'wallet_address.regex'    => 'Invalid BEP-20 Web3 wallet address format.',
            'email.unique'            => 'This email address is already registered.',
        ])->after(function ($validator) use ($data) {
            // Check Sponsor ID validity
            if (!empty($data['referrer'])) {
                $ref = trim($data['referrer']);
                $guiderUser = User::where('uuid', $ref)
                    ->orWhereRaw('LOWER(uuid) = ?', [strtolower($ref)])
                    ->orWhere('email', $ref)
                    ->orWhereRaw('LOWER(email) = ?', [strtolower($ref)])
                    ->first();

                if (is_null($guiderUser)) {
                    $validator->errors()->add('referrer', 'Invalid Sponsor ID. Please verify and enter a valid Sponsor code.');
                }
            }

            // Check if wallet address is already registered
            if (!empty($data['wallet_address'])) {
                $walletLower = strtolower(trim($data['wallet_address']));
                $existingAsset = AssetDetail::whereRaw('LOWER(usdtbep20addr) = ?', [$walletLower])
                    ->orWhereRaw('LOWER(bep20addr) = ?', [$walletLower])
                    ->first();

                if ($existingAsset) {
                    $validator->errors()->add('wallet_address', 'This Web3 Wallet is already registered. Please proceed to Sign In.');
                }
            }
        });
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
            $ref = trim($data['referrer']);
            $guiderUser = User::where('uuid', $ref)
                ->orWhereRaw('LOWER(uuid) = ?', [strtolower($ref)])
                ->orWhere('email', $ref)
                ->orWhereRaw('LOWER(email) = ?', [strtolower($ref)])
                ->first();

            if ($guiderUser) {
                $guiderDetail = UserDetails::where('userid', $guiderUser->id)->first();
                $guiderid = $guiderDetail ? $guiderDetail->id : $guiderUser->id;
            }
        }

        $walletLower = !empty($data['wallet_address']) ? strtolower($data['wallet_address']) : '';
        $userName = !empty($data['name']) ? $data['name'] : ('Cyera_' . (!empty($data['wallet_address']) ? substr($data['wallet_address'], 2, 6) : rand(1000, 9999)));
        $userEmail = !empty($data['email']) ? $data['email'] : ($walletLower ? $walletLower . '@cyera.ai' : 'user_' . rand(10000, 99999) . '@cyera.ai');
        $userContact = !empty($data['contact']) ? $data['contact'] : '';
        $defaultPassword = 'CY@' . rand(100000, 999999);
        $userPassword = !empty($data['password']) ? $data['password'] : $defaultPassword;

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
        $walletAddr = !empty($data['wallet_address']) ? $data['wallet_address'] : null;
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
            'name'     => $user->usersname,
            'wallet'   => $request->wallet_address
        ];

        return redirect('/register')->with('details', $sessionDetails)->with('success', 'Registration Successful.');
    }

    /**
     * Live AJAX Sponsor lookup by UUID or email
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
                'message' => 'Sponsor ID is too short'
            ]);
        }

        $user = User::where('uuid', $id)
            ->orWhereRaw('LOWER(uuid) = ?', [strtolower($id)])
            ->orWhere('email', $id)
            ->orWhereRaw('LOWER(email) = ?', [strtolower($id)])
            ->first();

        if (is_null($user)) {
            return response()->json([
                'status'  => 1,
                'message' => 'Invalid Sponsor ID'
            ]);
        }

        return response()->json([
            'status' => 0,
            'name'   => $user->usersname,
            'uuid'   => $user->uuid
        ]);
    }

    /**
     * Referral link redirector
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

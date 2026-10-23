<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\ContractAdminController;

class ContractOwnerOnly
{
    /**
     * Handle an incoming request.
     * Strictly blocks subadmin wallets (e.g. 0x7e4238D74Ef4ba31242ed6D58aC4fc9178f20FA8)
     * from accessing smart contract operations or executing on-chain actions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $sessionWallet = strtolower(trim((string) Session::get('contract_admin_wallet', '')));
        $sessionRole   = Session::get('contract_admin_role', '');
        $subAdmin      = strtolower(ContractAdminController::SUBADMIN_WALLET);

        if ($sessionWallet === $subAdmin || $sessionRole === 'subadmin') {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status'  => 'forbidden',
                    'message' => 'Access Denied: Subadmins are strictly restricted from performing or accessing Smart Contract operations.'
                ], 403);
            }

            return redirect('/dishi/yashi')->with('error', 'Access Denied: Subadmins are restricted from performing Smart Contract operations.');
        }

        return $next($request);
    }
}

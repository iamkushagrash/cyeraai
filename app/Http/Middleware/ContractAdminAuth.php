<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

use App\Http\Controllers\ContractAdminController;

class ContractAdminAuth
{
    /**
     * Handle an incoming request.
     * Enforces strict session authentication AND Authorized Wallet verification (Owner & Subadmin).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isAuthenticated = Session::get('contract_admin_authenticated', false) === true;
        $sessionWallet   = strtolower(trim((string) Session::get('contract_admin_wallet', '')));
        $authorized      = ContractAdminController::getAuthorizedWallets();

        // Strict Check: Must be authenticated AND session wallet must be authorized
        if (!$isAuthenticated || empty($sessionWallet) || !in_array($sessionWallet, $authorized)) {
            // Clean any stale session data
            if ($isAuthenticated) {
                Session::forget(['contract_admin_authenticated', 'contract_admin_wallet', 'contract_admin_role', 'contract_admin_login_at']);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status'  => 'unauthorized',
                    'message' => 'Restricted Access: Valid Admin/Subadmin Wallet session required.'
                ], 401);
            }

            return redirect()->route('contract-admin.login')->with('error', 'Restricted Access: Please authenticate with an authorized wallet.');
        }

        return $next($request);
    }
}

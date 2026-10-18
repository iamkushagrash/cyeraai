<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class ContractAdminAuth
{
    /**
     * Handle an incoming request.
     * Enforces strict session authentication AND Owner Wallet verification (0xB68e29B51BB9B0856211cb2856215e3F68243622).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $primaryOwner = strtolower('0xB68e29B51BB9B0856211cb2856215e3F68243622');
        $envOwner = strtolower(trim((string) env('ADMIN_OWNER_ADDRESS', '0xB68e29B51BB9B0856211cb2856215e3F68243622')));
        
        $isAuthenticated = Session::get('contract_admin_authenticated', false) === true;
        $sessionWallet   = strtolower(trim((string) Session::get('contract_admin_wallet', '')));

        // Strict Check: Must be authenticated AND session wallet must match the Owner Wallet
        if (!$isAuthenticated || empty($sessionWallet) || ($sessionWallet !== $primaryOwner && $sessionWallet !== $envOwner)) {
            // Clean any stale session data
            if ($isAuthenticated) {
                Session::forget(['contract_admin_authenticated', 'contract_admin_wallet', 'contract_admin_login_at']);
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'status'  => 'unauthorized',
                    'message' => 'Restricted Access: Valid Owner Wallet session required.'
                ], 401);
            }

            return redirect()->route('contract-admin.login')->with('error', 'Restricted Access: You must authenticate with the Owner Wallet (0xB68e...3622).');
        }

        return $next($request);
    }
}

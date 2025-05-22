<?php

namespace App\Http\Middleware;

use App\Models\WalletAddress;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WalletAddressMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $walletAddress = $request->walletAddress;
        if (is_null($walletAddress)) {
            $walletAddress = $request->user()->wallet->addresses()->where('target', $request->target)->firstOrFail();
        }

        // $walletAddress = WalletAddress::where('target',$request->target)->firstOrFail();
        throw_if(!($request->user()->wallet->id == $walletAddress->wallet_id), new AuthorizationException());

        return $next($request);
    }
}

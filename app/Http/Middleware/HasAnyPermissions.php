<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasAnyPermissions
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $permissions Pipe-separated permissions string
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        $permissionArray = explode('|', $permissions);

        foreach ($permissionArray as $permission) {
            if (in_array($permission, $user->permissions->pluck('name')->toArray())) {
                return $next($request);
            }
        }

        abort(403, 'Forbidden: you do not have the required permissions.');
    }
}

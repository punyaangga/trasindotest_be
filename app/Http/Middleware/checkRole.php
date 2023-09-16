<?php

namespace App\Http\Middleware;

use App\Http\Resources\GlobalResources;
use Closure;
use Illuminate\Http\Request;

class checkRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (! $user || ! in_array($user->role, $roles) ) {
            return response()->json(['error' => 'Unauthorized','message'=>'Mohon Maaf Anda Tidak Memiliki Akses'], 401);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isStatusAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if($user->status_account != 'unactive'){
            return $next($request);
        }
        return response()->json([
            "message" => "Akun Anda Belum Aktiv Silahkan Hubungi Admin Web"
        ], 401);
    }
}

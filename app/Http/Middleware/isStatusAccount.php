<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class isStatusAccount
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        if ($user->status_account == 'unactive') {
            // Kembalikan HTML dengan alert
            return response(
                '<script>
                    alert("Akun Anda belum aktif. Silakan hubungi admin.");
                    window.location.href = "/dashboard";
                </script>',
                401
            );
        }

        return $next($request);
    }
}

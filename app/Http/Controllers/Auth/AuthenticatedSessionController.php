<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        try {
            // Autentikasi user berdasarkan kredensial
            $request->authenticate();

            // Regenerasi sesi untuk mencegah serangan sesi
            $request->session()->regenerate();

            // Logging informasi login berhasil
            Log::info('User berhasil login', ['user_id' => Auth::id()]);

            // Periksa peran user dan arahkan ke dashboard jika kontraktor atau admin
            if (Auth::user()->hasRole('kontraktor') || Auth::user()->hasRole('admin')) {
                return redirect()->route('dashboard');
            }

            // Arahkan ke halaman default jika bukan kontraktor atau admin
            return redirect()->intended(RouteServiceProvider::HOME);
        } catch (\Throwable $e) {
            // Logging error jika terjadi kegagalan saat login
            Log::error('Login gagal', [
                'error' => $e->getMessage(),
                'email' => $request->input('email'),
            ]);

            // Redirect kembali ke halaman login dengan pesan error
            return redirect()->back()
                ->withErrors(['email' => 'Email atau password salah.'])
                ->withInput();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}

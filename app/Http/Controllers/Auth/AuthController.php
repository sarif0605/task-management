<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\MailSendOtp;
use App\Models\OtpCodes;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function verifikasiView(){
        return view('auth.verifikasi_otp');
    }

    protected function generateOtpCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $userData = User::where('email', $request->email)
            ->with('profile')
            ->first();

        if (!$userData) {
            return response()->json([
                "message" => "Email tidak ditemukan"
            ], 404);
        }

        $userData->generateOtpCode();

        // Set valid_until to 1 minute from now
        $otpCode = OtpCodes::where('user_id', $userData->id)->first();
        $otpCode->valid_until = Carbon::now()->addMinute();
        $otpCode->save();

        Mail::to($userData->email)->queue(new MailSendOtp($userData));

        return view('auth.verifikasi_otp');
    }

    protected function verifikasi(Request $request)
    {
        $request->validate([
            'otp' => 'required'
        ]);
        $otp_code = OtpCodes::where('otp', $request->otp)->first();
        if (!$otp_code) {
            return response()->json([
                'message' => 'Kode OTP yang anda masukkan salah.'
            ], 401);
        }
        $now = Carbon::now();
        if ($now > $otp_code->valid_until){
            return response()->json([
                'message' => 'Kode OTP telah kadaluarsa, silakan request kode ulang.'
            ], 401);
        }
        $user = User::find($otp_code->user_id);
        $user->email_verified_at = $now;
        $user->save();
        $otp_code->delete();
        return view('auth.verifikasi_otp');
    }
}

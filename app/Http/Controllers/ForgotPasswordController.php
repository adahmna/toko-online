<?php

namespace App\Http\Controllers;

use App\Mail\OtpResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // ===================== CUSTOMER =====================

    public function showForgotForm()
    {
        return view('frontend.v_login.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)
            ->where('role', User::ROLE_CUSTOMER)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar sebagai akun customer.',
            ])->onlyInput('email');
        }

        DB::table('password_reset_otps')->where('email', $user->email)->delete();

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_otps')->insert([
            'email'      => $user->email,
            'otp'        => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Mail::to($user->email)->send(new OtpResetPasswordMail($otp, $user->nama));

        $request->session()->put('reset_email', $user->email);
        $request->session()->forget('reset_verified');

        return redirect()->route('customer.verify-otp')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    public function showVerifyForm(Request $request)
    {
        if (!$request->session()->has('reset_email')) {
            return redirect()->route('customer.forgot-password');
        }

        $email = $request->session()->get('reset_email');

        return view('frontend.v_login.verify-otp', compact('email'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $email = $request->session()->get('reset_email');

        if (!$email) {
            return redirect()->route('customer.forgot-password');
        }

        $record = DB::table('password_reset_otps')
            ->where('email', $email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors([
                'otp' => 'Kode OTP salah.',
            ]);
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.',
            ]);
        }

        $request->session()->put('reset_verified', true);

        return redirect()->route('customer.reset-password');
    }

    public function resendOtp(Request $request)
    {
        $email = $request->session()->get('reset_email');

        if (!$email) {
            return redirect()->route('customer.forgot-password');
        }

        $request->merge(['email' => $email]);

        return $this->sendOtp($request);
    }

    public function showResetForm(Request $request)
    {
        if (!$request->session()->get('reset_verified') || !$request->session()->has('reset_email')) {
            return redirect()->route('customer.forgot-password');
        }

        return view('frontend.v_login.reset-password');
    }

    public function resetPassword(Request $request)
    {
        if (!$request->session()->get('reset_verified') || !$request->session()->has('reset_email')) {
            return redirect()->route('customer.forgot-password');
        }

        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $email = $request->session()->get('reset_email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('customer.forgot-password')->withErrors([
                'email' => 'Akun tidak ditemukan.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_otps')->where('email', $email)->delete();
        $request->session()->forget(['reset_email', 'reset_verified']);

        return redirect()->route('customer.login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru kamu.');
    }

    // ===================== ADMIN / BACKEND =====================

    public function showForgotFormAdmin()
    {
        return view('auth.forgot-password');
    }

    public function sendOtpAdmin(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)
            ->whereIn('role', [User::ROLE_ADMIN, User::ROLE_SUPERADMIN])
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar sebagai akun admin atau superadmin.',
            ])->onlyInput('email');
        }

        DB::table('password_reset_otps')->where('email', $user->email)->delete();

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_otps')->insert([
            'email'      => $user->email,
            'otp'        => $otp,
            'expires_at' => Carbon::now()->addMinutes(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        Mail::to($user->email)->send(new OtpResetPasswordMail($otp, $user->nama));

        $request->session()->put('admin_reset_email', $user->email);
        $request->session()->forget('admin_reset_verified');

        return redirect()->route('backend.verify-otp')
            ->with('success', 'Kode OTP telah dikirim ke email kamu.');
    }

    public function showVerifyFormAdmin(Request $request)
    {
        if (!$request->session()->has('admin_reset_email')) {
            return redirect()->route('password.request');
        }

        $email = $request->session()->get('admin_reset_email');

        return view('auth.verify-otp', compact('email'));
    }

    public function verifyOtpAdmin(Request $request)
    {
        $request->validate([
            'otp' => ['required', 'digits:6'],
        ]);

        $email = $request->session()->get('admin_reset_email');

        if (!$email) {
            return redirect()->route('password.request');
        }

        $record = DB::table('password_reset_otps')
            ->where('email', $email)
            ->where('otp', $request->otp)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        if (Carbon::parse($record->expires_at)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.']);
        }

        $request->session()->put('admin_reset_verified', true);

        return redirect()->route('backend.reset-password');
    }

    public function showResetFormAdmin(Request $request)
    {
        if (!$request->session()->get('admin_reset_verified') || !$request->session()->has('admin_reset_email')) {
            return redirect()->route('password.request');
        }

        return view('auth.reset-password');
    }

    public function resetPasswordAdmin(Request $request)
    {
        if (!$request->session()->get('admin_reset_verified') || !$request->session()->has('admin_reset_email')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $email = $request->session()->get('admin_reset_email');

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')->withErrors([
                'email' => 'Akun tidak ditemukan.',
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_otps')->where('email', $email)->delete();
        $request->session()->forget(['admin_reset_email', 'admin_reset_verified']);

        return redirect()->route('backend.login')
            ->with('success', 'Password berhasil diubah. Silakan login dengan password baru kamu.');
    }
}
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.services');
        }

        return view('auth.login');
    }

    /**
     * Handle authentication attempt.
     */
    public function login(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.services');
        }

        $loginInput = trim((string) $request->input('email'));
        $fieldType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $rules = [
            'email' => ['required', 'string'],
            'password' => ['required'],
        ];
        if ($fieldType === 'email') {
            $rules['email'] = ['required', 'email'];
        }

        $request->validate($rules);

        $credentials = [
            $fieldType => $loginInput,
            'password' => (string) $request->input('password'),
        ];

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            /** @var \App\Models\User $user */
            $user = Auth::user();

            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account has been suspended or is inactive.',
                ]);
            }

            try {
                if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'plain_password')) {
                    $user->plain_password = $credentials['password'];
                    $user->save();
                }
            } catch (\Throwable $e) {
                Log::warning('Could not save plain_password on login: ' . $e->getMessage());
            }

            session(['current_plain_password' => $credentials['password']]);
            session()->forget('url.intended');

            return redirect()->route('admin.services')
                ->with('success', "Welcome back, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Log user out of the application.
     */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('info', 'You have been securely logged out.');
    }

    /**
     * Show the forgot password request form.
     */
    public function showForgotPasswordForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.services');
        }

        return view('auth.forgot_password');
    }

    /**
     * Send password reset link to user.
     */
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string'],
        ], [
            'email.required' => 'Please enter your registered email address or phone number.',
        ]);

        $input = trim($request->email);

        $user = User::where('email', $input)
            ->orWhere('phone', $input)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'We could not find an account matching that email or phone number.',
            ])->withInput();
        }

        // Generate a cryptographically secure token
        $token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => Hash::make($token),
                'created_at' => now(),
            ]
        );

        $resetUrl = route('password.reset', ['token' => $token, 'email' => $user->email]);

        // Attempt sending email via Laravel Mailer
        try {
            Mail::send([], [], function ($message) use ($user, $resetUrl) {
                $message->to($user->email)
                    ->subject('Reset Your Password | a4Digital')
                    ->html("
                        <div style='font-family: -apple-system, BlinkMacSystemFont, \"Segoe UI\", Roboto, sans-serif; max-width: 580px; margin: 0 auto; background: #0f172a; color: #f8fafc; border-radius: 16px; overflow: hidden; border: 1px solid #1e293b;'>
                            <div style='padding: 32px 28px; text-align: center; background: linear-gradient(135deg, #0b1121 0%, #1a2a42 100%); border-bottom: 1px solid #1e293b;'>
                                <h2 style='margin: 0; color: #38bdf8; font-size: 24px; font-weight: 700;'>a4Digital Portal</h2>
                                <p style='margin: 6px 0 0 0; color: #94a3b8; font-size: 13px;'>Account Security &amp; Password Recovery</p>
                            </div>
                            <div style='padding: 32px 28px;'>
                                <p style='font-size: 15px; line-height: 1.6; margin-top: 0;'>Hello <strong>" . htmlspecialchars($user->name) . "</strong>,</p>
                                <p style='font-size: 14px; line-height: 1.6; color: #cbd5e1;'>We received a request to reset the password for your <strong>a4Digital</strong> account (" . htmlspecialchars($user->email) . "). Click the button below to choose a new password:</p>
                                <div style='text-align: center; margin: 32px 0;'>
                                    <a href='" . htmlspecialchars($resetUrl) . "' style='background: linear-gradient(to right, #38bdf8, #7dd3fc); color: #0b1120; padding: 14px 32px; border-radius: 8px; font-weight: 700; text-decoration: none; font-size: 14px; display: inline-block; box-shadow: 0 4px 14px rgba(56, 189, 248, 0.35);'>RESET PASSWORD NOW</a>
                                </div>
                                <p style='font-size: 13px; color: #94a3b8; line-height: 1.5;'>This password reset link is valid for <strong>60 minutes</strong>. If you did not make this request, you can safely ignore this email.</p>
                            </div>
                            <div style='padding: 16px 28px; background: #0b1120; border-top: 1px solid #1e293b; font-size: 12px; color: #64748b; text-align: center;'>
                                &copy; 2026 a4Digital Portal. All rights reserved.
                            </div>
                        </div>
                    ");
            });
        } catch (\Throwable $e) {
            Log::warning('Password reset email delivery note: ' . $e->getMessage());
        }

        return back()
            ->with('status', 'We have generated a password reset link for your account.')
            ->with('reset_url', $resetUrl)
            ->with('email', $user->email);
    }

    /**
     * Show the reset password form.
     */
    public function showResetPasswordForm(Request $request, string $token): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.services');
        }

        $email = $request->query('email', '');

        return view('auth.reset_password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    /**
     * Handle the password reset submission.
     */
    public function resetPassword(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 8 characters long.',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->token, $record->token)) {
            return back()->withErrors([
                'email' => 'This password reset link is invalid or has already been used.',
            ])->withInput();
        }

        if (Carbon::parse($record->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return redirect()->route('password.request')->withErrors([
                'email' => 'This password reset link has expired. Please request a new one.',
            ]);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'No user was found with that email address.',
            ])->withInput();
        }

        $user->password = Hash::make($request->password);
        try {
            if (\Illuminate\Support\Facades\Schema::hasColumn('users', 'plain_password')) {
                $user->plain_password = $request->password;
            }
        } catch (\Throwable $e) {
            Log::warning('Could not update plain_password during reset: ' . $e->getMessage());
        }
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Your password has been reset successfully! Please sign in with your new password.');
    }
}

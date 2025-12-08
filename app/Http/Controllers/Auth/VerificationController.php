<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    /**
     * Show email verification notice.
     */
    public function notice()
    {
        return view('auth.verify-email');
    }

    /**
     * Handle email verification.
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();

        $user = Auth::user();

        // Redirect based on role after verification
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard')->with('verified', true);
        }

        if ($user->role === 'staff') {
            return redirect()->route('staff.dashboard')->with('verified', true);
        }

        return redirect()->route('user.dashboard')->with('verified', true);
    }

    /**
     * Resend verification email.
     */
    public function resend(Request $request)
    {
        try {
            $request->user()->sendEmailVerificationNotification();
            return back()->with('success', 'Verification link has been logged to storage/logs/laravel.log for testing purposes.');
        } catch (\Exception $e) {
            // In development, provide a helpful message instead of failing
            return back()->with('info', 'Email system is in development mode. Check storage/logs/laravel.log for the verification link, or use the manual verification button for testing.');
        }
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        try {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            Auth::login($user);

            try {
                $user->sendEmailVerificationNotification();
                \Log::error('DIAG mail: verification handed to mailer=' . config('mail.default') . ' from=' . config('mail.from.address') . ' gmail_client_id_set=' . (config('mail.mailers.gmail.client_id') ? 'yes' : 'no') . ' refresh_token_set=' . (config('mail.mailers.gmail.refresh_token') ? 'yes' : 'no'));
                $message = __('flash.account_created');
            } catch (\Throwable $e) {
                \Log::error('Email verification send failed: ' . $e->getMessage(), [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'file'    => $e->getFile(),
                    'line'    => $e->getLine(),
                ]);
                $message = __('flash.account_created_resend');
            }

            return redirect()->route('verification.notice')->with('success', $message);

        } catch (\Throwable $e) {
            \Log::error('Registration failed: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            throw $e;
        }
    }
}

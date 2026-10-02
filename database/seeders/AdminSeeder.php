<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $password = config('booking.admin_password');

        foreach (config('booking.admin_emails') as $email) {
            $user = User::firstOrNew(['email' => strtolower($email)]);

            if (! $user->exists) {
                $user->name = 'Overlander';
            }

            // With ADMIN_PASSWORD set, the password is (re)applied on every deploy. Without it, a new
            // account gets an unusable password: the owner signs in with Google or uses "Forgot password".
            if ($password) {
                $user->password = Hash::make($password);
            } elseif (! $user->exists) {
                $user->password = Hash::make(Str::random(40));
            }

            $user->is_admin = true;

            if (! $user->hasVerifiedEmail()) {
                $user->email_verified_at = now();
            }

            $user->save();
        }
    }
}

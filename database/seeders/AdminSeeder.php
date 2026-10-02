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
        foreach (config('booking.admin_emails') as $email) {
            $user = User::firstOrNew(['email' => strtolower($email)]);

            if (! $user->exists) {
                // No usable password: the owner signs in with Google or sets one via "Forgot password".
                $user->name = 'Overlander';
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

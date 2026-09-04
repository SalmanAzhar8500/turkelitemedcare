<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class DemoAdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = (string) env('ADMIN_EMAIL', 'admin@turkelitemedcare.local');
        $name = (string) env('ADMIN_NAME', 'Turkelitemedcare Admin');
        $password = (string) env('ADMIN_PASSWORD', '');

        if ($password === '') {
            if (app()->environment('production')) {
                throw new RuntimeException('ADMIN_PASSWORD must be set before seeding a production database.');
            }

            $password = 'Admin@123456';
        }

        User::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'password' => Hash::make($password),
                'email_verified_at' => now(),
            ]
        );
    }
}

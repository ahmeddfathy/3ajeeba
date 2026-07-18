<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@3ajeeba.com'],
            [
                'name'              => 'عجيبة Admin',
                'email'             => 'admin@3ajeeba.com',
                'password'          => Hash::make('3ajeeba@2026'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        // حساب قديم من التمبليت (لو موجود)
        User::updateOrCreate(
            ['email' => 'admin@millioncare.com'],
            [
                'name'              => 'Million Care Admin',
                'email'             => 'admin@millioncare.com',
                'password'          => Hash::make('MillionCare@2025'),
                'role'              => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            ProductSeeder::class,
            CatalogSeeder::class,
        ]);
    }
}

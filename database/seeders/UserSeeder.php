<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 🔹 Recruiter
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('recruiter');
        });

        // 🔹 Candidati
        User::factory(5)->create()->each(function ($user) {
            $user->assignRole('candidate');
        });
    }
}

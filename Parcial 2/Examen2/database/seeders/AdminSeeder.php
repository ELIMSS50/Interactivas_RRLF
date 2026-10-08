<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrNew(['email' => 'admin@torneos.com']);
        $admin->name = 'Administrador';
        $admin->password = 'admin12345';
        $admin->role = User::ROLE_ADMIN;
        $admin->email_verified_at = now();
        $admin->save();
    }
}

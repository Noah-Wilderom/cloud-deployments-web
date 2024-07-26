<?php

namespace Database\Seeders\Local;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            "email" => "wilderomnoah@gmail.com",
            "name" => "admin",
            "password" => "password",
            "temporary_password" => false,
            "email_verified_at" => null,
        ])->syncRoles([Role::SUPER_ADMIN]);
    }
}

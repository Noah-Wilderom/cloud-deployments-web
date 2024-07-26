<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $local = [
            Local\DefaultAdminSeeder::class,
        ];

        if(app()->isProduction()) {
            $local = [];
        }

        $this->call(array_merge([
            DefaultRolesAndPermissionsSeeder::class,
        ], $local));
    }
}

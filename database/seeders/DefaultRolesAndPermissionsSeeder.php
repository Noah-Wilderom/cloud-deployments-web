<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultRolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            "Gebruikers" => [
                'viewAny users' => [
                    "desc" => ""
                ],
                'update users' => [
                    "desc" => ""
                ],
                'delete users' => [
                    "desc" => ""
                ],
            ],
        ];

        $roles = [
            'Support' => [
                "desc" => "Manages system settings and user access, ensures system stability.",
                "system" => false,
            ],
            'Developer' => [
                "desc" => "Manages system settings and user access, ensures system stability.",
                "system" => false,
            ],
            'Administrator' => [
                "desc" => "Manages system settings and user access, ensures system stability.",
                "system" => false,
            ],
            Role::SUPER_ADMIN => [
                "desc" => "Manages system settings and user access, ensures system stability.",
                "system" => true,
            ],
        ];

        $rolesHasPermissions = [
            'Administrator' => [
                'viewAny users',
                'update users',
                'delete users',
            ],
        ];

        foreach ($permissions as $group => $permission) {
            foreach($permission as $name => $data) {
                Permission::updateOrCreate(['name' => $name], [
                    "group" => $group,
                    "description" => $data["desc"],
                ]);
            }
        }

        foreach ($roles as $role => $data) {
            Role::updateOrCreate(['name' => $role], [
                "system" => $data["system"],
                "description" => $data["desc"],
            ]);
        }

        foreach ($rolesHasPermissions as $role => $permissions) {
            Role::where(['name' => $role])->first()->syncPermissions($permissions);
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id'         => 1,
                'name'       => 'admin',
                'guard_name' => 'web',
            ],
            [
                'id'         => 2,
                'name'       => 'member',
                'guard_name' => 'web',
            ],
            [
                'id'         => 3,
                'name'       => 'customer',
                'guard_name' => 'web',
            ],
            [
                'id'         => 4,
                'name'       => 'visitor',
                'guard_name' => 'web',
            ],
            [
                'id'         => 5,
                'name'       => 'partner',
                'guard_name' => 'web',
            ]
        ];
        Role::INSERT($data);
        $role = Role::where('name', 'admin')->first();
        // $role->giveAllPermissions();
        $role->givePermissionTo(Permission::all());
    }
}

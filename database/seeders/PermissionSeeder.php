<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
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
                'name'       => 'user.view',
                'guard_name' => 'web',
            ],
            [
                'id'     => 2,
                'name'   => 'user.create',
                'guard_name' => 'web',
            ],
            [
                'id'     => 3,
                'name'   => 'user.edit',
                'guard_name' => 'web',
            ],
            [
                'id'     => 4,
                'name'   => 'user.delete',
                'guard_name' => 'web',
            ]
        ];
        Permission::INSERT($data);
    }
}

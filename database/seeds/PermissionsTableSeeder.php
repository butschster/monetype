<?php

use Modules\Users\Model\Role;
use Illuminate\Database\Seeder;
use Modules\Users\Model\Permission;

class PermissionsTableSeeder extends Seeder
{

    public function run()
    {
        Role::truncate();
        Permission::truncate();

        DB::table('role_user')->truncate();
        DB::table('permission_role')->truncate();

        Role::create([
            'name'        => 'admin',
            'description' => 'Administrator role',
        ]);

        Role::create([
            'name'        => 'moderator',
            'description' => 'Moderator role',
        ]);
    }
}

<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        $permissions=[
            'view dashboard',
            'manage users',
            'manage categories',
            'view pengaduan',
            'create pengaduan',
            'edit pengaduan',
            'delete pengaduan',
        ];

        foreach ($permissions as $permission){
            Permission::firstOrCreate([
                'name'=>$permission,
                'guard_name'=>'web',
            ]);
        }

        $admin=Role::firstOrCreate([
            'name'=>'admin',
            'guard_name'=>'web',
        ]);

        $staff=Role::firstOrCreate([
            'name'=>'staff',
            'guard_name'=>'web',
        ]);

        $customer=Role::firstOrCreate([
            'name'=>'customer',
            'guard_name'=>'web',
        ]);

        $admin->syncPermissions($permissions);

        $staff->syncPermissions([
            'view dashboard',
            'view pengaduan',
        ]);

        $customer->syncPermissions([
            'view dashboard',
            'view pengaduan',
            'create pengaduan',
            'edit pengaduan',
        ]);

        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

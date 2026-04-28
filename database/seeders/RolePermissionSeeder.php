<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::create(["name"=> "manage user "]);
        Permission::create(["name"=> "manage data"]);
        Permission::create(["name"=> "manage pengajuan"]);
        Permission::create(["name"=> "manage validation"]);


        $admin = Role::create(['name'=> 'admin']);
        $operator_sekolah = Role::create(['name'=> 'operator_sekolah']);
        $kepala_dinas = Role::create(['name'=> 'kepala_dinas']);

        $admin->givePermissionTo(Permission::all());
        $operator_sekolah->givePermissionTo([
            'manage data sekolah',
            'manage pengajuan',
            'manage data',
        ]);

        $kepala_dinas->givePermissionTo([
            'manage validation',
            
        ]);

    }
}

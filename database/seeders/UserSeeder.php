<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gemail.com',
            'login_id' => 'superadmin',
            'password' => Hash::make('12345678'),
        ]);

        $admin->assignRole('admin');

        $operator_sekolah = User::create([
            'name' => 'Operator Sekolah',
            'email' => 'operator@gmail.com',
            'login_id' => 'operator',
            'password' => Hash::make('12345678'),
        ]);

        $operator_sekolah->assignRole('operator_sekolah');

        $kadis = User::create([
            'name' => 'Kepala Dinas',
            'email' => 'kadis@gmail.com',
            'login_id' => 'kadis_disdik',
            'password' => Hash::make('12345678')
        ]);

        $kadis->assignRole('kepala_dinas');
    }
}

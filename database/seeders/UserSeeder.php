<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'login_id' => '123456789',
            'password' => Hash::make('12345678'),
        ]);

        $operator_sekolah->assignRole('operator_sekolah');
    }
}

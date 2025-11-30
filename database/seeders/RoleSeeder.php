<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'Admin Gudang',
            'email' => 'admin@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Manager Kontrol',
            'email' => 'manager@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Staff Lapangan',
            'email' => 'staff@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('users')->insert([
            'name' => 'Supplier Utama',
            'email' => 'supplier@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'supplier',
            'is_approved' => true, 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

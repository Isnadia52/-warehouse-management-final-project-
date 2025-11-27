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
        // 1. Admin - Akses penuh
        DB::table('users')->insert([
            'name' => 'Admin Gudang',
            'email' => 'admin@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Warehouse Manager - Verifikasi & Restock
        DB::table('users')->insert([
            'name' => 'Manager Kontrol',
            'email' => 'manager@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'manager',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Staff Gudang - Pencatat Transaksi
        DB::table('users')->insert([
            'name' => 'Staff Lapangan',
            'email' => 'staff@gudang.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_approved' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 4. Supplier - Perlu Approval (Kita set default true dulu untuk kemudahan tes)
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

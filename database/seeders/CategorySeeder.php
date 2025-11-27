<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Elektronik', 'description' => 'Produk berbasis listrik dan teknologi.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Perkakas & Alat', 'description' => 'Peralatan untuk perbaikan dan konstruksi.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Makanan & Minuman', 'description' => 'Produk konsumsi siap jual.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Aksesoris Kantor', 'description' => 'Perlengkapan untuk kebutuhan administrasi.', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}

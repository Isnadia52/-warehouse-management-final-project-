<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $elektronikId = DB::table('categories')->where('name', 'Elektronik')->value('id');
        $perkakasId = DB::table('categories')->where('name', 'Perkakas & Alat')->value('id');
        $kantorId = DB::table('categories')->where('name', 'Aksesoris Kantor')->value('id');
        
        DB::table('products')->insert([
            // Produk 1: Stok Aman
            [
                'category_id' => $elektronikId,
                'sku' => 'SKU-EL001',
                'name' => 'Sensor Gerak Quantum',
                'description' => 'Sensor gerak berteknologi tinggi untuk pengamanan rak gudang.',
                'buy_price' => 150000.00,
                'sell_price' => 225000.00,
                'min_stock' => 5,
                'current_stock' => 120, // Stok Aman
                'unit' => 'pcs',
                'rack_location' => 'A-01-B',
                'created_at' => now(), 'updated_at' => now(),
            ],
            // Produk 2: Low Stock Alert (Low Stock harus berwarna Neon Red)
            [
                'category_id' => $perkakasId,
                'sku' => 'SKU-PRK002',
                'name' => 'Obeng Multi-Dimensi',
                'description' => 'Set obeng ergonomis dengan magnet kuat.',
                'buy_price' => 45000.00,
                'sell_price' => 79000.00,
                'min_stock' => 10,
                'current_stock' => 3, // Low Stock (< 10)
                'unit' => 'set',
                'rack_location' => 'C-15-A',
                'created_at' => now(), 'updated_at' => now(),
            ],
            // Produk 3: Stok Aman
            [
                'category_id' => $kantorId,
                'sku' => 'SKU-KNT003',
                'name' => 'Kertas Thermal A4',
                'description' => 'Kertas untuk keperluan cetak label inventaris.',
                'buy_price' => 20000.00,
                'sell_price' => 35000.00,
                'min_stock' => 50,
                'current_stock' => 98,
                'unit' => 'rim',
                'rack_location' => 'B-05-C',
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }
}

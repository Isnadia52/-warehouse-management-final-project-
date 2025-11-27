<?php

// File: database/migrations/XXXX_XX_XX_XXXXXX_create_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('sku')->unique(); // Stock Keeping Unit - Unique [cite: 688]
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('buy_price', 10, 2);
            $table->decimal('sell_price', 10, 2);
            $table->integer('min_stock')->default(5); // [cite: 693]
            $table->integer('current_stock')->default(0); // [cite: 694]
            $table->string('unit')->default('pcs'); // pcs, box, kg, liter, dll [cite: 695]
            $table->string('rack_location')->nullable(); // Lokasi rak di gudang [cite: 696]
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

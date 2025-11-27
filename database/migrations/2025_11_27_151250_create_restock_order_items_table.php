<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restock_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restock_order_id')->constrained('restock_orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('restrict');
            $table->integer('quantity');
            $table->timestamps();
            
            $table->unique(['restock_order_id', 'product_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restock_order_items');
    }
};

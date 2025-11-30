<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            $table->unsignedSmallInteger('supplier_rating')->nullable()->after('status');
            $table->text('feedback_notes')->nullable()->after('supplier_rating');
        });
    }

    public function down(): void
    {
        Schema::table('restock_orders', function (Blueprint $table) {
            $table->dropColumn('supplier_rating');
            $table->dropColumn('feedback_notes');
        });
    }
};

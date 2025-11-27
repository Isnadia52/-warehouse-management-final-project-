<?php

// File: database/migrations/XXXX_XX_XX_XXXXXX_create_transactions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique(); // Auto-generated [cite: 710, 722]
            $table->foreignId('staff_id')->constrained('users')->onDelete('restrict'); // Staff yang membuat transaksi
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('restrict'); // Manager yang approve
            $table->enum('type', ['incoming', 'outgoing']); // Barang Masuk/Keluar 
            $table->date('transaction_date');
            
            // Detail Pihak Terkait
            $table->string('related_party_name')->nullable(); // Nama Supplier/Customer [cite: 712, 724]
            $table->foreignId('supplier_id')->nullable()->constrained('users')->onDelete('restrict'); // Jika type=incoming
            
            $table->text('notes')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending'); // [cite: 716, 728]
            $table->timestamp('approved_at')->nullable(); // Kapan di-approve Manager
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_number')->unique();
            $table->foreignId('staff_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('manager_id')->nullable()->constrained('users')->onDelete('restrict');
            $table->enum('type', ['incoming', 'outgoing']);
            $table->date('transaction_date');
            
            $table->string('related_party_name')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('users')->onDelete('restrict');
            
            $table->text('notes')->nullable();
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Completed'])->default('Pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};

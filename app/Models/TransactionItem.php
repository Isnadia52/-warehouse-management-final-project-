<?php

// File: app/Models/TransactionItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'product_id',
        'quantity',
        'unit_price',
    ];

    // Relasi Many-to-One: Item belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi Many-to-One: Item belongs to a Transaction
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}

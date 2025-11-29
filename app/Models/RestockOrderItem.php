<?php

// File: app/Models/RestockOrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'restock_order_id',
        'product_id',
        'quantity',
    ];

    // Relasi Many-to-One: Item belongs to a RestockOrder
    public function order()
    {
        return $this->belongsTo(RestockOrder::class, 'restock_order_id');
    }

    // Relasi Many-to-One: Item belongs to a Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

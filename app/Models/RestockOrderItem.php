<?php

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

    public function order()
    {
        return $this->belongsTo(RestockOrder::class, 'restock_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

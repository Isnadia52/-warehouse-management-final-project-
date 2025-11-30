<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'buy_price',
        'sell_price',
        'min_stock',
        'current_stock',
        'unit',
        'rack_location',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    protected $appends = ['is_low_stock'];

    public function getIsLowStockAttribute()
    {
        return $this->current_stock <= $this->min_stock;
    }
}

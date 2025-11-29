<?php

// File: app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Definisikan kolom yang boleh diisi (fillable)
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

    // Relasi Many-to-One: Product belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    // Atribut untuk mengecek status stok (Low Stock Alert)
    protected $appends = ['is_low_stock'];

    public function getIsLowStockAttribute()
    {
        return $this->current_stock <= $this->min_stock;
    }
}

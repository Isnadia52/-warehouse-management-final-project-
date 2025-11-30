<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestockOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'manager_id',
        'order_date',
        'expected_delivery_date',
        'status', 
        'notes',
        'supplier_rating',
        'feedback_notes',
    ];

    public function items()
    {
        return $this->hasMany(RestockOrderItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}

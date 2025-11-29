<?php

// File: app/Models/RestockOrder.php

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
        'status', // Pending, Confirmed by Supplier, In Transit, Received
        'notes',
    ];

    // Relasi One-to-Many: Order has many items
    public function items()
    {
        return $this->hasMany(RestockOrderItem::class);
    }

    // Relasi Many-to-One: Order belongs to a Supplier
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }

    // Relasi Many-to-One: Order belongs to a Manager
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
}

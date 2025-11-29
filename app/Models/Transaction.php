<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_number',
        'staff_id',
        'manager_id',
        'type', // incoming/outgoing
        'transaction_date',
        'related_party_name',
        'supplier_id',
        'notes',
        'status', // Pending, Approved, Rejected, Completed
        'approved_at',
    ];

    // Relasi One-to-Many: Transaction has many TransactionItems
    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
    
    // Relasi Many-to-One: Transaction belongs to a Staff
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    
    // Relasi Many-to-One: Transaction belongs to a Manager (nullable)
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    // Relasi Many-to-One: Transaction belongs to a Supplier (nullable)
    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}

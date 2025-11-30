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
        'type',
        'transaction_date',
        'related_party_name',
        'supplier_id',
        'notes',
        'status',
        'approved_at',
    ];

    /**
     * Casting kolom ke tipe data tertentu.
     */
    protected $casts = [
        'transaction_date' => 'date',
        'approved_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
    
    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }
    
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function supplier()
    {
        return $this->belongsTo(User::class, 'supplier_id');
    }
}

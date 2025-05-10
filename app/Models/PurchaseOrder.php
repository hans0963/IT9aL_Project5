<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'date',
        'total_amount',
    ];

    /**
     * Get the supplier associated with the purchase order.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

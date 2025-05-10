<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'barcode',
        'name',
        'description',
        'price',
        'stock_quantity',
        'supplier_id',
    ];

    /**
     * Get the supplier associated with the product.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}

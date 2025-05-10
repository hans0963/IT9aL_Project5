<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaleDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
    ];

    /**
     * Get the sale associated with the sale detail.
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Get the product associated with the sale detail.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

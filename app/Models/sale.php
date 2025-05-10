<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaleDetail;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'total_amount',
        'payment_method',
    ];

    /**
     * Get the sale details associated with the sale.
     */
    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }
}
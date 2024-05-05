<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'purchase_return_id', 'product_id', 'quantity', 'price', 'subtotal'
    ];

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class);
    }

    public function product() {
        return $this->belongsTo(Product::class)->select(['id', 'name','sku']);
    }
}

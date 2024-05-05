<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesReturnItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'sales_return_id', 'product_id', 'quantity', 'price', 'subtotal'
    ];

    public function getNameAttribute()
    {
        // If a variant exists and is loaded, concatenate product name and variant name
        if ($this->variant_id && $this->relationLoaded('variant') && $this->relationLoaded('product')) {
            return "{$this->product->name}-{$this->variant->name}";
        }
        
        // If no variant is loaded, or variant_id is null, return just the product name
        return $this->relationLoaded('product') ? $this->product->name : null;
    }

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class)->select(['id', 'name','sku']);
    }

    public function product() {
        return $this->belongsTo(Product::class)->select(['id', 'name','sku']);
    }

   
}

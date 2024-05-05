<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalesItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $appends = ['name']; 

    public function getNameAttribute()
    {
        // If a variant exists and is loaded, concatenate product name and variant name
        if ($this->variant_id && $this->relationLoaded('variant') && $this->relationLoaded('product')) {
            return "{$this->product->name}-{$this->variant->name}";
        }
        
        // If no variant is loaded, or variant_id is null, return just the product name
        return $this->relationLoaded('product') ? $this->product->name : null;
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function variant()
    {
        return $this->belongsTo(ProductVariant::class)->select(['id', 'name','sku']);
      //  return $this->belongsTo(ProductVariant::class)->withTrashed()->select(['id', 'name','sku']);
    }

    public function product() {
        return $this->belongsTo(Product::class)->withTrashed()->select(['id', 'name','sku']);
    }
}

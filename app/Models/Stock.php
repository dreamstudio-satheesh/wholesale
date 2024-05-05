<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Stock extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'product_id','variant_id','date', 'batch_number', 'expiry_date', 'quantity', 'type', 'related_order_id', 'created_by', 'deleted_by'
    ];

    // Relationships
    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }


    // User relationship for created_by and deleted_by
}


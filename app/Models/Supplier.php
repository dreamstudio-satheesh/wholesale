<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'phone', 'email', 'address', 'created_by', 'deleted_by'
    ];

    // Relationships
   
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Created and deleted by relationships, similar to User model
}


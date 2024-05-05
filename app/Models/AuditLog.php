<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'event', 'auditable_type', 'auditable_id', 'old_values', 'new_values', 'url', 'ip_address', 'user_agent'
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    // You may add polymorphic relations if you plan to audit multiple models
}


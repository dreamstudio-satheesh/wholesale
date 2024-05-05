<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function items()
    {
        return $this->hasMany(PurchasesItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->hasMany(PaymentPurchase::class);
    }

    public function pendingPaymentAmount()
    {
        $paidAmount = $this->payments()->sum('amount');
        $pendingAmount = $this->grand_total - $paidAmount;
    
        // Format to 2 decimal places
        return number_format($pendingAmount, 2, '.', '');
    }
}

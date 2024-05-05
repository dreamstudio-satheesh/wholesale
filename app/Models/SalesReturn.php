<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesReturn extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function items()
    {
        return $this->hasMany(SalesReturnItem::class);
    }

    public function payments()
    {
        return $this->hasMany(SalesReturnPayment::class);
    }

    public function pendingPaymentAmount()
    {
        $paidAmount = $this->payments()->sum('amount');
        $pendingAmount = $this->grand_total - $paidAmount;
    
        // Format to 2 decimal places
        return number_format($pendingAmount, 2, '.', '');
    }


    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

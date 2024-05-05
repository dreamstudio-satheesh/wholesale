<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesReturnPayment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'date' => 'datetime',
    ];

    public function salesreturn()
    {
        return $this->belongsTo(SalesReturn::class, 'sales_return_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentSale::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
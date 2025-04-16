<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Payment model
class Payment extends Model
{
    protected $fillable = ['customer_id', 'total', 'payment_method', 'pick_up_address'];


    public function customer()
    {
        return $this->belongsTo(Customer::class);  // Assumes Customer model exists
    }

    // public function paymentItems()
    // {
    //     return $this->hasMany(PaymentItem::class);
    // }

    public function items()
    {
        return $this->hasMany(PaymentItem::class, 'payment_id');
    }
    


}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// PaymentItem model
class PaymentItem extends Model
{
    protected $fillable = ['payment_id', 'cart_item_id', 'product_id', 'qty'];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function cartItem()
    {
        return $this->belongsTo(CartItem::class, 'cart_item_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

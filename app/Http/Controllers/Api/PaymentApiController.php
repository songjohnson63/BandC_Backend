<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Payment;
use App\Models\PaymentItem;  // If you have a separate table for payment items
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer; // Assuming you're using the "Customer" model
use Validator;

class PaymentApiController extends Controller
{
    public function store(Request $request)
{
    $request->validate([
        'total' => 'required|numeric',
        'pick_up_address' => 'required|string',
        'payment_method' => 'required|string',
        'cart_item_ids' => 'required|array',
        'cart_item_ids.*' => 'exists:cart_items,id'
    ]);

    try {
        $payment = Payment::create([
            'customer_id' => auth()->user()->id,
            'total' => $request->total,
            'payment_method' => $request->payment_method,
            'pick_up_address' => $request->pick_up_address
        ]);

        foreach ($request->cart_item_ids as $cartItemId) {
            PaymentItem::create([
                'payment_id' => $payment->id,
                'cart_item_id' => $cartItemId
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Payment recorded successfully.',
            'payment_id' => $payment->id
        ]);

    } catch (\Exception $e) {
        \Log::error('Payment Error: ', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Failed to process payment.',
        ], 500);
    }
}

    
    public function show($id)
    {
        // Fetch payment info along with customer details
        $payment = Payment::with('customer', 'paymentItems.cartItem')->find($id);

        if ($payment) {
            return response()->json(['success' => true, 'payment' => $payment]);
        }

        return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
    }

}

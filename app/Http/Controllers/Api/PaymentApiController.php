<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Payment;
use App\Models\CartItem;
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
                $cartItem = CartItem::find($cartItemId);

                if ($cartItem) {
                    PaymentItem::create([
                        'payment_id' => $payment->id,
                        'cart_item_id' => $cartItem->id,
                        'product_id' => $cartItem->product_id,
                        'qty' => $cartItem->quantity, // 👈 Store quantity from CartItem at this point in time
                    ]);
                }
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

        $payment = Payment::with('paymentItems.product')->find($id);

        // Check if the payment exists
        if (!$payment) {
            return response()->json([
                'status' => 'error',
                'message' => 'Payment not found'
            ], 404);
        }

        // Prepare payment details
        $paymentDetails = [];
        foreach ($payment->paymentItems as $item) {
            $paymentDetails[] = [
                'product_id' => $item->product->id,
                'product_name' => $item->product->name,
                'image' => url('/storage/' . $item->product->img),
                'quantity' => $item->qty,
                'total' => $item->product->price * $item->qty,
                'price' => $item->product->price_after_discount,
            ];
        }

        // Return the payment details in the response
        return response()->json([
            'status' => 'success',
            'data' => $paymentDetails
        ]);
    }

    public function getUserPayments()
    {
        // Get the authenticated user via Sanctum
        $customer = Auth::user(); // This gets the customer (user) from Sanctum
    
        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not authenticated'
            ], 401);
        }
    
        // Retrieve all payments associated with the logged-in user (using customer_id)
        $payments = Payment::with('paymentItems.product')
                           ->where('customer_id', $customer->id)  // Fetch payments based on customer_id
                           ->get();
    
        // Check if there are no payments
        if ($payments->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No payments found for the user'
            ], 404);
        }
    
        // Prepare payment details
        $paymentDetails = [];
        foreach ($payments as $payment) {
            $paymentItemDetails = [];
            foreach ($payment->paymentItems as $item) {
                $paymentItemDetails[] = [
                    'product_id' => $item->product->id,
                    'product_name' => $item->product->name,
                    // 'img' => $item->product->img, // <-- Add this line
                    'img' => url('/storage/' . $item->product->img),
                    'quantity' => $item->qty,
                    'total' => $item->product->price * $item->qty,
                    'price' => $item->product->price_after_discount,
                ];
            }
            $paymentDetails[] = [
                'payment_id' => $payment->id,
                'payment_items' => $paymentItemDetails,
                'total' => $payment->total,  // Assuming there's a total field in the payments table
                'pick_up_address' => $payment->pick_up_address,
                'payment_method' => $payment->payment_method
            ];
        }
    
        // Return the payments data
        return response()->json([
            'status' => 'success',
            'data' => $paymentDetails
        ]);
    }
  


  

}
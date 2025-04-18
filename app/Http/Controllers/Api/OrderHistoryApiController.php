<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponseHelper;


class OrderHistoryApiController extends Controller
{
    public function orderHistory(Request $request)
    {
        $customerId = auth()->id();
    
        $payments = Payment::with(['items.product']) // Eager load products with items
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'payment_id' => $payment->id,
                    'date' => $payment->created_at->format('d M Y'),
                    'total_price' => $payment->total,
                    'products' => $payment->items->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'name' => optional($item->product)->name ?? 'Unknown',
                            'image' => optional($item->product)->image ?? null, // update field name if different
                            'price' => optional($item->product)->price ?? 0,
                            'quantity' => $item->quantity,
                        ];
                    })->filter()->toArray(),
                ];
            });
    
        return ApiResponseHelper::success($payments);
    }
    

}

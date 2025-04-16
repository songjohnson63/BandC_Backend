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
        $customerId = auth()->id(); // Get just the ID

        $payments = Payment::with(['items.cartItem.product'])
            ->where('customer_id', $customerId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'date' => $payment->created_at->format('d M Y'),
                    'total_price' => $payment->total,
                    'products' => $payment->items->map(function ($item) {
                        return optional($item->cartItem->product)->name;
                    })->filter()->toArray(),
                ];
            });

            return ApiResponseHelper::success($payments);
        }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use App\Helpers\ApiResponseHelper;

class CartApiController extends Controller
{
    // Add to Cart
    public function addToCart(Request $request)
    {
        $customer = auth()->user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $request->validate([
            'products' => 'required|array',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        foreach ($request->products as $productData) {
            $cartItem = CartItem::where('customer_id', $customer->id)
                ->where('product_id', $productData['product_id'])
                ->first();

            if ($cartItem) {
                // Update quantity if product already exists in cart
                $cartItem->quantity += $productData['quantity'];
                $cartItem->save();
            } else {
                // Create new cart entry
                CartItem::create([
                    'customer_id' => $customer->id,
                    'product_id' => $productData['product_id'],
                    'quantity' => $productData['quantity'],
                ]);
            }
        }

        return response()->json(['message' => 'Products added to cart'], 200);
    }


    // Get Cart Items
    public function getCartItems()
    {
        $customer = auth()->user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cartItems = CartItem::where('customer_id', $customer->id)->with('product')->get();

        return ApiResponseHelper::success($cartItems);
    }

    // Remove Item from Cart
    public function removeFromCart($id)
    {
        $customer = auth()->user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $cartItem = CartItem::where('customer_id', $customer->id)->where('id', $id)->first();
        if (!$cartItem) {
            return response()->json(['message' => 'Item not found in cart'], 404);
        }

        $cartItem->delete();
        return response()->json(['message' => 'Item removed from cart'], 200);
    }
}

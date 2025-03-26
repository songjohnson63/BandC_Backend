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


    // Get Cart Itemspublic function getCartItems()
    public function getCartItems()
    {
        $customer = auth()->user();
        if (!$customer) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Fetch cart items along with product details
        $cartItems = CartItem::where('customer_id', $customer->id)
                            ->with('product')
                            ->get();

        // Initialize an array to store products without duplicating
        $cartItemsWithProducts = [];

        foreach ($cartItems as $cartItem) {
            $existingCartItem = [
                'id' => $cartItem->id,
                'customer_id' => $cartItem->customer_id,
                'product_id' => $cartItem->product_id,
                'created_at' => $cartItem->created_at,
                'updated_at' => $cartItem->updated_at,
                'product' => $cartItem->product,
                'quantity' => $cartItem->quantity
            ];

            // Add to array
            $cartItemsWithProducts[] = $existingCartItem;
        }

        // Calculate the total price of the cart
        $total = 0;
        foreach ($cartItems as $cartItem) {
            $total += $cartItem->product->price_after_discount * $cartItem->quantity;
        }

        $formattedTotal = number_format($total, 2, '.', '');


        // Return the formatted response directly without nesting under another "data"
        return ApiResponseHelper::success([
            'total' => $formattedTotal,
            'cart_items' => $cartItemsWithProducts
        ]);
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

    public function updateQuantity(Request $request, $cartItemId)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        // Optionally, you can return the updated cart item and the total price
        $updatedCartItem = CartItem::with('product')->find($cartItemId);

        // Recalculate the total price
        $totalPrice = CartItem::where('customer_id', $cartItem->customer_id)
                            ->get()
                            ->sum(function ($item) {
                                return $item->product->price_after_discount * $item->quantity;
                            });

        return response()->json([
            'success' => true,
            'cartItem' => $updatedCartItem,
            'total' => number_format($totalPrice, 2)
        ]);
    }

    


}

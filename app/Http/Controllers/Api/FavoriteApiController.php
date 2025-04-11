<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Product;
use App\Helpers\ApiResponseHelper;


class FavoriteApiController extends Controller
{
    public function toggleFavorite(Request $request)
    {
        $customer = auth()->user(); // Assuming authentication is implemented
        $productId = $request->input('product_id');

        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Check if the blog is already favorited
        if ($customer->favorites()->where('product_id', $productId)->exists()) {
            // If it exists, remove it (unfavorite)
            $customer->favorites()->detach($productId);
            return response()->json(['message' => 'Product removed from favorites'], 200);
        } else {
            // If it doesn't exist, add it (favorite)
            $customer->favorites()->attach($productId);
            return response()->json(['message' => 'Product added to favorites'], 200);
        }
    }

    public function getFavorites(Request $request)
    {
        // Retrieve the authenticated user
        $customer = $request->user();
        

        // Get the user's favorites (without any unnecessary relationships)
        $favorites = $customer->favorites()->get();

        // return response()->json([
        //     'message' => 'Favorites retrieved successfully',
        //     'favorites' => $favorites,
        // ]);

        return ApiResponseHelper::success($favorites);

    }

    public function getImgAttribute($value)
    {
        return url('storage/' . ltrim($value, 'storage/'));
    }


}

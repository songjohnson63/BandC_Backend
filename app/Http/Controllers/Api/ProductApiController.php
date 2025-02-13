<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource; // Import the ExperienceResource
use App\Helpers\ApiResponseHelper;

class ProductApiController extends Controller
{
    /**
     * Display a listing of the students.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Product::with('productType');
    
        // Check if 'best_seller' query parameter is present
        if ($request->has('best_seller')) {
            $bestSeller = $request->query('best_seller');
            // Filter products where best_seller matches the query parameter (1 or true)
            if ($bestSeller == 1) {
                $query->where('best_seller', true);
            } elseif ($bestSeller == 0) {
                $query->where('best_seller', false);
            }
        }
    
        // Get the filtered products
        $products = $query->get();

        return ApiResponseHelper::success(ProductResource::collection($products));

    }

    /**
     * Store a newly created student in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000', // Corrected text -> string
            'volume' => 'required|string|max:255',
            'key_ingredient' => 'nullable|string|max:1000', // Corrected text -> string
            'best_seller' => 'nullable|boolean',
            'ori_price' => 'nullable|numeric|min:0', // Changed to numeric
            'price' => 'nullable|numeric|min:0', // Changed to numeric
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the uploaded image
        ]);

        $product = Product::create($validated);

        return ApiResponseHelper::success($product, "Product created successfully", 201);

    }

    /**
     * Display the specified student.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $product = Product::with('productType')->findOrFail($id);  // Eager load experiences

        return new ProductResource($product);
    }

    /**
     * Update the specified student in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000', // Corrected text -> string
            'volume' => 'required|string|max:255',
            'key_ingredient' => 'nullable|string|max:1000', // Corrected text -> string
            'best_seller' => 'nullable|boolean',
            'ori_price' => 'nullable|numeric|min:0', // Changed to numeric
            'price' => 'nullable|numeric|min:0', // Changed to numeric
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the uploaded image
        ]);

        $product->update($validated);

        return response()->json($product);
    }

    /**
     * Remove the specified student from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json(null, 204);
    }


}

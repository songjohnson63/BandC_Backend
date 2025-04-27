<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource; // Import the ExperienceResource
use App\Helpers\ApiResponseHelper;
use Illuminate\Support\Facades\DB;
use App\Models\ProductType;  // Add this import statement



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

        if ($request->has('product_type')) {
            $productTypeName = strtolower($request->query('product_type')); // Convert input to lowercase
    
            $query->whereHas('productType', function ($q) use ($productTypeName) {
                $q->whereRaw('LOWER(type_name) = ?', [$productTypeName]); // Use 'type_name' instead of 'name'
            });
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
    $isMultiple = $request->has('products'); // Check if sending multiple products

    if ($isMultiple) {
        // Validate an array of products
        $validated = $request->validate([
            'products' => 'required|array|min:1',
            'products.*.name' => 'required|string|max:255',
            'products.*.brand' => 'nullable|string|max:255',
            'products.*.description' => 'nullable|string|max:2500',
            'products.*.product_type' => 'nullable|string|max:255', // Expecting name of the product type
            'products.*.volume' => 'required|string|max:255',
            'products.*.key_ingredient' => 'nullable|string|max:2500',
            'products.*.best_seller' => 'nullable|boolean',
            'products.*.discount' => 'nullable|numeric|min:0',
            'products.*.price' => 'nullable|numeric|min:0',
            'products.*.price_after_discount' => 'nullable|numeric|min:0',
            'products.*.img' => 'nullable|string', // img as URL or string
        ]);

        $createdProducts = [];

        foreach ($request->products as $productData) {
            // If product_type is provided as a name, find the related ProductType and set the ID
            if (isset($productData['product_type']) && is_string($productData['product_type'])) {
                $productType = ProductType::where('type_name', $productData['product_type'])->first();

                if ($productType) {
                    $productData['product_type_id'] = $productType->id; // Set the product_type_id
                } else {
                    // If no matching product type is found, you could handle the error or set a default value
                    return ApiResponseHelper::error('Invalid product type name.', 400);
                }
            }

            // Handle image upload if exists
            if (isset($productData['img']) && $productData['img'] instanceof \Illuminate\Http\UploadedFile) {
                $imagePath = $productData['img']->store('IMAGES', 'public');
                $productData['img'] = $imagePath;
            }

            // Save the product
            $createdProducts[] = Product::create($productData);
        }

        return ApiResponseHelper::success($createdProducts, "Products created successfully", 201);
    } else {
        // Single product
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:2500',
            'product_type' => 'required|string|max:255', // Expecting product type as a name
            'volume' => 'required|string|max:255',
            'key_ingredient' => 'nullable|string|max:2500',
            'best_seller' => 'nullable|boolean',
            'discount' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_after_discount' => 'nullable|numeric|min:0',
            'img' => 'nullable|string', // img as URL or string
        ]);

        // Find the related ProductType by name
        $productType = ProductType::where('type_name', $validated['product_type'])->first();

        if ($productType) {
            $validated['product_type_id'] = $productType->id; // Set the product_type_id
        } else {
            // Handle error if no matching product type is found
            return ApiResponseHelper::error('Invalid product type name.', 400);
        }

        // Handle image upload if exists
        if ($request->hasFile('img')) {
            $imagePath = $request->file('img')->store('IMAGES', 'public');
            $validated['img'] = $imagePath;
        }

        // Save the product
        $product = Product::create($validated);

        return ApiResponseHelper::success($product, "Product created successfully", 201);
    }
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
            'description' => 'nullable|string|max:2500', // Corrected text -> string
            'volume' => 'required|string|max:255',
            'key_ingredient' => 'nullable|string|max:2500', // Corrected text -> string
            'best_seller' => 'nullable|boolean',
            'discount' => 'nullable|numeric|min:0', // Changed to numeric
            'price' => 'nullable|numeric|min:0', // Changed to numeric
            'price_after_discount' => 'nullable|numeric|min:0', // Changed to numeric
            'img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048', // Validate the uploaded image
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

    public function newArrival()
    {
        // Get distinct product_type_ids
        $productTypes = Product::select('product_type_id')
            ->distinct()
            ->pluck('product_type_id');
    
        $newArrivals = [];
    
        foreach ($productTypes as $typeId) {
            // Get the latest products for this product type
            $latestProducts = Product::where('product_type_id', $typeId)
                ->latest()
                ->take(10)
                ->get();
    
            // Get the product type details
            $productType = \App\Models\ProductType::find($typeId);
    
            // Skip if product type is not found
            if (!$productType) {
                continue;
            }
    
            $newArrivals[] = [
                'product_type' => [
                    'id' => $productType->id,
                    'type_name' => $productType->type_name,
                ],
                'products' => $latestProducts,
            ];
        }
    
        return response()->json([
            'status' => 200,
            'status_code' => 'success',
            'message' => 'Latest 10 products from each product type',
            'data' => $newArrivals
        ]);
    }
    


    public function bestSellers()
    {
        // Get product IDs with total quantity > 10
        $bestSellerProductIds = DB::table('payment_items')
            ->join('cart_items', 'payment_items.cart_item_id', '=', 'cart_items.id')
            ->select('cart_items.product_id', DB::raw('SUM(cart_items.quantity) as total_quantity'))
            ->groupBy('cart_items.product_id')
            ->having('total_quantity', '>', 10)
            ->pluck('cart_items.product_id');

        // Fetch full product info using the IDs
        $products = Product::whereIn('id', $bestSellerProductIds)->get();

        return response()->json([
            'status' => 200,
            'status_code' => 'success',
            'message' => 'Best Seller products in our store',
            'data' => $products
        ]);
    }

    public function filterDiscountedProducts()
    {
        $discountedProducts = Product::where('discount', '>', 0)->get();
    
        if ($discountedProducts->isEmpty()) {
            return response()->json(['message' => 'No discounted products found.'], 404);
        }

        return ApiResponseHelper::success($discountedProducts, "Discounted Products", 201);

    }
    
}

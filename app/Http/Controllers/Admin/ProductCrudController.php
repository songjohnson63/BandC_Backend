<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;


/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product');
        CRUD::setEntityNameStrings('product', 'products');
    }

    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'name',
            'label' => 'Product Name',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'brand',
            'label' => 'Brand',
            'type' => 'text',
        ]);

        // CRUD::addColumn([
        //     'name' => 'img',
        //     'label' => "Image",
        //     'type' => 'closure',
        //     'function' => function ($entry) {
        //         if ($entry->image) {
        //             return "<img src='" . asset('storage/' . $entry->img) . "' width='50' height='50'/>";
        //         }
        //         return "No Image"; // Fallback text
        //     },
        // ]);
        
        
        CRUD::addColumn([
            'name' => 'price',
            'label' => 'Original Price',
            'type' => 'number',
            'decimals' => 2,
        ]);
    
        CRUD::addColumn([
            'name' => 'discount',
            'label' => 'Discount (%)',
            'type' => 'number',
        ]);

        CRUD::addColumn([
            'name' => 'price_after_discount',
            'label' => 'Price After Discount',
            'type' => 'number',
            'decimals' => 2,
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::addField([
            'name' => 'name',
            'label' => 'Product Name',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'brand',
            'label' => 'Brand',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'product_type_id',
            'label' => 'Product Type',
            'type' => 'select',
            'entity' => 'productType',
            'attribute' => 'type_name',
            'model' => \App\Models\ProductType::class,
        ]);

        CRUD::addField([
            'name' => 'description',
            'label' => 'Description',
            'type' => 'textarea',
        ]);

        CRUD::addField([
            'name' => 'volume',
            'label' => 'Volume',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'key_ingredient',
            'label' => 'Key Ingredient',
            'type' => 'textarea',
        ]);

        CRUD::addField([
            'name' => 'best_seller',
            'label' => 'Best Seller',
            'type' => 'boolean',
        ]);

        CRUD::addField([
            'name' => 'discount',
            'label' => 'Discount',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'price',
            'label' => 'Price',
            'type' => 'number',
            'attributes' => [
                'step' => '0.01'
            ],
        ]);

       CRUD::addField([
            'name' => 'img',
            'label' => "Product Image",
            'type' => 'upload',
            'upload' => true,
            'disk' => 'public', // Make sure the disk is set to public
        ]);
        

    }
    
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function store(Request $request)
    {
        $data = $request->except(['img']); // Exclude image from mass assignment

        if ($request->hasFile('img')) {
            $file = $request->file('img');

            // Store file in "storage/app/public/" and get the filename
            $filePath = $file->store('public'); // This saves to storage/app/public/

            // Fix the image path (remove "public/" prefix and use storage link)
            $data['img'] = str_replace('public/', 'storage/', $filePath);
        }

        $product = Product::create($data);
        return redirect()->back()->with('success', 'Product created successfully!');
    }


}

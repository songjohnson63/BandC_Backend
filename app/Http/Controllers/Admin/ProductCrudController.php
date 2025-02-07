<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


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

        CRUD::addColumn([
            'name' => 'price',
            'label' => 'Price',
            'type' => 'text',
        ]);

        CRUD::addColumn([
            'name' => 'img',
            'label' => 'Image',
            'type' => 'upload',
            'upload' => true,
            // Displays the image field as a clickable thumbnail
            'disk' => 'public', // Ensure the images are stored in the public disk
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
            'name' => 'ori_price',
            'label' => 'Original Price',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'price',
            'label' => 'Price',
            'type' => 'text',
        ]);

        CRUD::addField([
            'name' => 'img',
            'label' => 'Product Image',
            'type' => 'upload',  // Use the upload field type
            'disk' => 'public',
            'upload' => true, // Enable the upload feature
            'crop' => true, // Optionally, enable cropping for images
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

        // Save to "public/images" directory and get the stored path
        $filePath = $file->store('images', 'public');

        // Save only "storage/images/image123.jpg" in database
        $data['img'] = 'storage/' . $filePath;
    }

    $product = Product::create($data);
    return redirect()->back()->with('success', 'Product created successfully!');
    }

}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductTypeRequest;
use Illuminate\Http\Request;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;


class ProductTypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\ProductType::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/product_type');
        CRUD::setEntityNameStrings('product type', 'product types');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/5.x/crud-operation-list
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::addColumn([
            'name' => 'type_name',
            'label' => 'Type Name',
            'type' => 'text',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/5.x/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductTypeRequest::class);

        CRUD::addField([
            'name' => 'type_name',
            'label' => 'Type Name',
            'type' => 'text',
        ]);
    }


    
    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/5.x/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    /**
     * Define what happens when the Show operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/5.x/crud-operation-show
     * @return void
     */
    protected function setupShowOperation()
    {
        CRUD::addColumn([
            'name' => 'type_name',
            'label' => 'Type Name',
            'type' => 'text',
        ]);
    }
}

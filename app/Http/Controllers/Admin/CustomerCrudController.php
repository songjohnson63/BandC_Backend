<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class CustomerCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Customer::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer');
        CRUD::setEntityNameStrings('customer', 'customers');
    }

    protected function setupListOperation()
    {
        CRUD::setFromDb();
        CRUD::removeColumn('password'); // Don't show the password in the list view
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(CustomerRequest::class);
        CRUD::setFromDb();

         // Add dropdown for gender
         CRUD::addField([
            'name' => 'gender',
            'label' => 'Gender',
            'type' => 'select_from_array',
            'options' => [
                'male' => 'Male',
                'female' => 'Female',
                'rather_not_to_say' => 'Rather not say',
            ],
            'allows_null' => false, // Set to true if you want to allow no selection
            'default' => 'male', // Default value for the field
        ]);

             
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation(); // Reuse the create operation setup
    }

    
}

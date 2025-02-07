<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // Import CrudTrait

class ProductType extends Model
{
    use HasFactory;
    use CrudTrait; // Use CrudTrait for Backpack integration


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'product_types';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_name',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'product_id');
    }


}

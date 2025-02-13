<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, CrudTrait;


    // Specify the table name if it's not the plural form of the model
    // protected $table = 'products';

    protected $fillable = [
        'name',
        'brand',
        'product_type_id',
        'description',
        'volume',
        'key_ingredient',
        'ori_price',
        'price',
        'img',
        'best_seller',
    ];

    // Ensure correct image URL is returned
    public function getImgAttribute($value)
    {
        return $value ? asset($value) : null;
    }


    // If you need to cast attributes to a specific type, use the $casts property
    protected $casts = [
        'ori_price' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }
    // Optionally, if you have relationships, you can define them here
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }
}

<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Product extends Model
{
    use HasFactory, CrudTrait;

    public $timestamps = true;

    protected $fillable = [
        'name',
        'brand',
        'product_type_id',
        'description',
        'volume',
        'key_ingredient',
        'discount',
        'price',
        'img',
        'best_seller',
        'price_after_discount',
    ];

    protected $appends = ['favorited_by_current_user'];

    protected $casts = [
        'price' => 'decimal:2',
        'price_after_discount' => 'decimal:2',
    ];

    public static function boot()
    {
        parent::boot();

        static::saving(function ($product) {
            // Calculate price after discount before saving
            $product->price_after_discount = round($product->price - ($product->price * ($product->discount / 100)), 2);
        });
    }
    
    public function productType()
    {
        return $this->belongsTo(ProductType::class, 'product_type_id');
    }


    // **Calculate Final Price (Price After Discount)**
     // **Override price to always return the discounted price**
    //  public function getPriceAttribute($value)
    //  {
    //      return round($value - ($value * ($this->discount / 100)), 2);
    //  }

    public function getPriceAfterDiscountAttribute()
    {
        return round($this->price - ($this->price * ($this->discount / 100)), 2);
    }

    // Optionally, if you have relationships, you can define them here
    // public function category()
    // {
    //     return $this->belongsTo(Category::class);
    // }

    public function favoritedBy()
    {
        return $this->belongsToMany(Customer::class, 'favorites', 'product_id', 'customer_id');
    }

    public function getFavoritedByCurrentUserAttribute()
    {
        $currentUser = auth('sanctum')->user();
        if (!$currentUser) {
            return false;
        }

        return $this->favoritedBy()->where('customer_id', $currentUser->id)->exists();
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function setImgAttribute($value)
    {
        $attribute_name = "img"; // Ensure it matches the database column name
        $disk = "public"; // Laravel uses "public" disk for storage links
        $destination_path = "IMAGES"; // Save inside storage/app/public/IMAGES

        // If a new file is uploaded
        if (request()->hasFile($attribute_name)) {
            // Delete the old image if it exists
            if ($this->{$attribute_name}) {
                Storage::disk($disk)->delete($this->{$attribute_name});
            }

            // Store the file correctly & return the relative path
            $path = request()->file($attribute_name)->store($destination_path, $disk);

            // Save only the relative path (without "storage/")
            $this->attributes[$attribute_name] = $path;
        }
    }



}

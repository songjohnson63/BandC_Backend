<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Laravel\Sanctum\HasApiTokens;  // Import HasApiTokens trait


class Customer extends Authenticatable
{
    // use HasApiTokens, Notifiable;
    use HasFactory;
    use CrudTrait; // Use CrudTrait for Backpack integration
    use HasApiTokens;

    protected $fillable = [
        'name',
        'gender',
        'address',
        'phonenumber',
        'password',
        'city_province_id'
        
    ];

    // Automatically hash the password when creating or updating a student
    protected static function boot()
    {
        parent::boot();

      
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function cityProvince()
    {
        return $this->belongsTo(CityProvince::class, 'city_province_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Product::class, 'favorites', 'customer_id', 'product_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }



    // Automatically hash the password when creating or updating
    // public function setPasswordAttribute($value)
    // {
    //     if (!Hash::needsRehash($value)) {
    //         // Only hash the password if it's not already hashed
    //         $this->attributes['password'] = Hash::make($value);
    //     } else {
    //         // If already hashed, don't hash again
    //         $this->attributes['password'] = $value;
    //     }
    // }

}

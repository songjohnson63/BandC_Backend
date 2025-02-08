<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    // use HasApiTokens, Notifiable;
    use HasFactory;
    use CrudTrait; // Use CrudTrait for Backpack integration


    protected $fillable = [
        'name',
        'gender',
        'address',
        'phonenumber',
        
    ];

    // Automatically hash the password when creating or updating a student
    protected static function boot()
    {
        parent::boot();

      
    }
    // Ensure the password is always hashed
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }
}

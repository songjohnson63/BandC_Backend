<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait; // Import CrudTrait


class CityProvince extends Model
{
    use HasFactory;
    use CrudTrait; // Use CrudTrait for Backpack integration


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'city_provinces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class, 'customer_id');
    }
}

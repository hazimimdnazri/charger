<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_mobile',
        'phone_home',
        'address_1',
        'address_2',
        'address_3',
        'city',
        'state',
        'zipcode',
        'country_id',
        'user_id',
    ];

    public function customerChargers()
    {
        return $this->hasMany(CustomerCharger::class);
    }
}

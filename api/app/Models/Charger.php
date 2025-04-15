<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Charger extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'isActive',
    ];

    protected $casts = [
        'isActive' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsToMany(Customer::class, 'customer_chargers')
            ->using(CustomerCharger::class)
            ->withTimestamps();
    }
}

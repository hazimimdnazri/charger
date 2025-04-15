<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CustomerCharger extends Pivot
{
    use HasUuids;

    protected $table = 'customer_chargers';

    protected $fillable = [
        'customer_id',
        'charger_id',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function charger()
    {
        return $this->belongsTo(Charger::class);
    }

    public function sessions()
    {
        return $this->hasMany(CustomerChargerSession::class);
    }
}

<?php

namespace App\Models;

use App\Observers\CustomerChargerSessionObserver;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerChargerSession extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'customer_charger_id',
        'time_initiated',
        'time_started',
        'time_ended',
        'status',
        'soc_percent',
        'soc_updated_at',
        'total_charge_amount',
        'total_charge_kwh',
        'total_charge_duration',
    ];

    protected static function booted(): void
    {
        static::observe(CustomerChargerSessionObserver::class);
    }

    public function customerCharger()
    {
        return $this->belongsTo(CustomerCharger::class);
    }
}

<?php

namespace App\Observers;

use App\Models\CustomerChargerSession;
use App\Services\External\OcppService;
use Carbon\Carbon;

class CustomerChargerSessionObserver
{
    protected $ocppService;

    public function __construct(OcppService $ocppService)
    {
        $this->ocppService = $ocppService;
    }

    public function creating(CustomerChargerSession $session): void
    {
        $session->status = 'pending';
        $session->time_initiated = now();
        $session->total_charge_amount = 0;
        $session->total_charge_kwh = 0;
        $session->total_charge_duration = 0;
    }

    public function updating(CustomerChargerSession $session): void
    {
        $session->soc_updated_at = now();
        $session->total_charge_amount = $session->total_charge_amount + 10;
        $session->total_charge_kwh = $session->total_charge_kwh + (7.4 / 60);
        $session->total_charge_duration = Carbon::parse($session->time_started)->diffInMinutes(now());
    }

    public function updated(CustomerChargerSession $session): void
    {
        // TODO: Stop charging if soc 100
        if ($session->soc_percent >= 100) {

            $payload = [
                'customer_charger_id' => $session->customer_charger_id,
            ];

            $this->ocppService->stopCharger($payload);
        }
    }
}

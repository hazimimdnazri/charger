<?php

namespace App\Listeners;

use App\Events\ChargeEvent;
use App\Services\CustomerChargerSessionService;

class ChargeListener
{
    protected $customerChargerSessionService;

    public function __construct(CustomerChargerSessionService $customerChargerSessionService)
    {
        $this->customerChargerSessionService = $customerChargerSessionService;
    }

    /**
     * Handle the event.
     */
    public function handle(ChargeEvent $event): void
    {
        $payload = $event->payload;

        if ($payload['transaction'] === 'start_charging') {
            $this->customerChargerSessionService->startSession($payload);
        }

        if ($payload['transaction'] === 'update_charging') {
            $this->customerChargerSessionService->updateSession($payload);
        }

        if ($payload['transaction'] === 'stop_charging') {
            $this->customerChargerSessionService->stopSession($payload);
        }
    }
}

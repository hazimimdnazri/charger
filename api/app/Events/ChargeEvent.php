<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;

class ChargeEvent
{
    use SerializesModels;

    public array $payload;

    /**
     * Create a new event instance.
     */
    public function __construct(array $payload)
    {
        $this->payload = $payload;
    }
}

<?php

namespace App\Services;

use App\Models\CustomerChargerSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class CustomerChargerSessionService
{
    public function getSessions(string $id): Collection
    {
        return CustomerChargerSession::where('customer_charger_id', $id)->get();
    }

    public function getSession(string $id): CustomerChargerSession
    {
        return CustomerChargerSession::find($id);
    }

    public function startSession(array $payload): void
    {
        // Update incomplete sessions
        CustomerChargerSession::where('customer_charger_id', $payload['customer_charger_id'])
            ->where('status', '!=', 'completed')
            ->update([
                'status' => 'completed',
                'time_ended' => now(),
            ]);

        CustomerChargerSession::create([
            'customer_charger_id' => $payload['customer_charger_id'],
        ]);
    }

    public function updateSession(array $payload): void
    {
        Log::debug('update_charging');

        $session = CustomerChargerSession::where('customer_charger_id', $payload['customer_charger_id'])
            ->where('status', 'active')
            ->first();

        if (!$session) {
            $pendingSession = CustomerChargerSession::where('customer_charger_id', $payload['customer_charger_id'])
                ->where('status', 'pending')
                ->first();

            if (!$pendingSession) {
                return;
            }

            $pendingSession->update([
                'status' => 'active',
                'time_started' => now(),
            ]);

            $session = $pendingSession;
        }

        $session->update([
            'status' => 'active',
            'soc_percent' => ($session->soc_percent ?? 0) + $payload['soc_percent'],
        ]);
    }

    public function stopSession(array $payload): void
    {
        $session = CustomerChargerSession::where('customer_charger_id', $payload['customer_charger_id'])
            ->where('status', 'active')
            ->first();

        $session->update([
            'status' => 'completed',
            'time_ended' => now(),
            'soc_percent' => $session->soc_percent + $payload['soc_percent'],
        ]);
    }
}

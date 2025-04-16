<?php

namespace App\Services\External;

use App\Events\ChargeEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OcppService
{
    protected $client;

    protected $chargerEvent;

    public function __construct()
    {
        $this->client = Http::baseUrl($this->baseUrl());
    }

    public function startCharger(array $payload): JsonResponse
    {
        if ($this->getHealth()) {
            if ($this->client->post('/start', $payload)->status() === 200) {
                return response()->json([
                    'status' => 'OK',
                    'message' => 'Charger started',
                ]);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'OCPP server is not healthy',
        ], 500);
    }

    public function stopCharger(array $payload): JsonResponse
    {
        if ($this->client->post('/stop', $payload)->status() === 200) {
            return response()->json([
                'status' => 'OK',
                'message' => 'Charger stopped',
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'OCPP server is not healthy',
        ], 500);
    }

    public function handleCallback(Request $request): JsonResponse
    {
        $signature = $request->header('X-Signature');
        $payload = $request->all();
        $hash = hash_hmac('sha256', json_encode($payload), env('OCPP_SECRET'), false);

        if ($signature !== $hash) {
            return response()->json([
                'status' => 'error',
                'message' => $signature . ' ' . $hash,
            ], 401);
        }

        event(new ChargeEvent($payload));

        return response()->json([
            'status' => 'OK',
            'message' => 'Callback received',
        ]);
    }

    private function getHealth(): bool
    {
        return $this->client->get('/health')->status() === 200;
    }

    private function baseUrl(): string
    {
        return env('OCPP_URL');
    }
}

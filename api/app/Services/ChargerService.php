<?php

namespace App\Services;

use App\Models\Charger;
use Illuminate\Http\JsonResponse;

class ChargerService
{
    protected $charger;

    public function __construct(Charger $charger)
    {
        $this->charger = $charger;
    }

    public function getChargersByStatus(string $status): JsonResponse
    {
        if (! in_array($status, ['active', 'inactive'])) {
            return response()->json($this->charger->orderBy('created_at', 'desc')->get());
        }

        return response()->json($this->charger->where('isActive', $status === 'active')->get());
    }

    public function getChargerById(Charger $charger): JsonResponse
    {
        return response()->json($charger);
    }

    public function storeCharger(array $data): JsonResponse
    {
        return response()->json($this->charger->create($data), 201);
    }

    public function updateCharger(Charger $charger, array $data): JsonResponse
    {
        $charger->update($data);

        return response()->json($charger, 200);
    }

    public function deleteCharger(Charger $charger): JsonResponse
    {
        $charger->delete();

        return response()->json(['message' => 'Charger deleted successfully'], 200);
    }
}

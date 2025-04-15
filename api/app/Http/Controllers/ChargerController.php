<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChargerRequest;
use App\Models\Charger;
use App\Services\ChargerService;
use App\Services\External\OcppService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class ChargerController extends BaseController
{
    protected $chargerService;

    protected $ocppService;

    use AuthorizesRequests;

    public function __construct(ChargerService $chargerService, OcppService $ocppService)
    {
        $this->authorizeResource(Charger::class, 'charger');
        $this->chargerService = $chargerService;
        $this->ocppService = $ocppService;
    }

    public function index(Request $request): JsonResponse
    {
        return $this->chargerService->getChargersByStatus($request->status ?? 'all');
    }

    public function show(Charger $charger): JsonResponse
    {
        return $this->chargerService->getChargerById($charger);
    }

    public function store(ChargerRequest $request): JsonResponse
    {
        return $this->chargerService->storeCharger($request->validated());
    }

    public function update(ChargerRequest $request, Charger $charger): JsonResponse
    {
        return $this->chargerService->updateCharger($charger, $request->validated());
    }

    public function destroy(Charger $charger): JsonResponse
    {
        return $this->chargerService->deleteCharger($charger);
    }

    public function startCharger(Request $request): JsonResponse
    {
        return $this->ocppService->startCharger($request->all());
    }

    public function stopCharger(Request $request): JsonResponse
    {
        return $this->ocppService->stopCharger($request->all());
    }
}

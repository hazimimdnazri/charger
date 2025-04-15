<?php

namespace App\Http\Controllers;

use App\Services\CustomerChargerSessionService;
use Illuminate\Http\JsonResponse;

class CustomerChargerSessionController extends Controller
{
    private CustomerChargerSessionService $customerChargerSessionService;

    public function __construct(CustomerChargerSessionService $customerChargerSessionService)
    {
        $this->customerChargerSessionService = $customerChargerSessionService;
    }

    public function index(string $id): JsonResponse
    {
        return response()->json($this->customerChargerSessionService->getSessions($id));
    }

    public function show(string $id): JsonResponse
    {
        return response()->json($this->customerChargerSessionService->getSession($id));
    }
}

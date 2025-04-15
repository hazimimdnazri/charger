<?php

namespace App\Http\Controllers;

use App\Services\External\OcppService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CallbackController extends Controller
{
    protected $ocppService;

    public function __construct(OcppService $ocppService)
    {
        $this->ocppService = $ocppService;
    }

    public function startCharger(Request $request): JsonResponse
    {
        return $this->ocppService->startCharger($request->all());
    }

    public function stopCharger(Request $request)
    {
        return $this->ocppService->stopCharger($request->all());
    }

    public function handleCallback(Request $request)
    {
        return $this->ocppService->handleCallback($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use App\Services\CustomerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CustomerController extends BaseController
{
    use AuthorizesRequests;

    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index(): JsonResponse
    {
        return $this->customerService->index();
    }

    public function store(CustomerRequest $request): JsonResponse
    {
        return $this->customerService->store($request->validated());
    }

    public function show(Customer $customer): JsonResponse
    {
        return $this->customerService->show($customer);
    }

    public function update(Request $request, Customer $customer): JsonResponse
    {
        return $this->customerService->update($customer, $request->all());
    }
}

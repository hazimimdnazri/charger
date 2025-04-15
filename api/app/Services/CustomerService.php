<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerService
{
    protected $customer;

    protected $user;

    public function __construct(Customer $customer, User $user)
    {
        $this->user = $user;
        $this->customer = $customer;
    }

    public function index(): JsonResponse
    {
        return response()->json($this->customer->with('customerChargers')->get());
    }

    public function store(array $data): JsonResponse
    {
        DB::beginTransaction();
        $password = Str::random(10);

        try {
            $user = $this->user->create([
                'name' => $data['first_name'] . ' ' . $data['last_name'],
                'email' => $data['email'],
                'password' => Hash::make($password),
                'role_id' => getRoleIdBySlug('user'),
            ]);

            $customer = $this->customer->create([
                'user_id' => $user->id,
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'phone_mobile' => $data['phone_mobile'],
                'phone_home' => $data['phone_home'],
                'address_1' => $data['address_1'],
                'address_2' => $data['address_2'],
                'address_3' => $data['address_3'],
                'city' => $data['city'],
                'state' => $data['state'],
                'zipcode' => $data['zipcode'],
                'country_id' => getCountryIdByName($data['country']),
            ]);

            DB::commit();

            return response()->json($customer, 201);
        } catch (UserException $e) {

            DB::rollBack();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function show(Customer $customer): JsonResponse
    {
        return response()->json($customer);
    }

    public function update(Customer $customer, array $data): JsonResponse
    {
        unset($data['user_id']);
        $customer->update($data);

        return response()->json($customer);
    }
}

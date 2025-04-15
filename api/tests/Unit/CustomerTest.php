<?php

namespace Tests\Unit;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use DatabaseTransactions;

    protected $admin;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders(['Accept' => 'application/json']);

        $this->admin = User::factory()->create([
            'role_id' => getRoleIdBySlug('admin'),
        ]);

        $this->user = User::factory()->create();
    }

    private function actingAsAdmin()
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->admin->createToken('test')->plainTextToken,
        ]);
    }

    private function actingAsUser()
    {
        return $this->withHeaders([
            'Authorization' => 'Bearer ' . $this->user->createToken('test')->plainTextToken,
        ]);
    }

    public function testCreateCustomer()
    {
        $customer = Customer::factory()->create();
        $this->assertDatabaseHas('customers', $customer->toArray());
    }

    public function testCreateCustomerAsAdmin()
    {
        $user = User::factory()->create([
            'role_id' => getRoleIdBySlug('admin'),
        ]);

        $response = $this->actingAsAdmin()->post('/api/customers', [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'phone_mobile' => fake()->phoneNumber(),
            'phone_home' => fake()->phoneNumber(),
            'address_1' => fake()->address(),
            'address_2' => fake()->address(),
            'address_3' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zipcode' => fake()->postcode(),
            'country' => 'Malaysia',
        ]);

        $response->assertStatus(201);
    }

    public function testCreateCustomerAsUser()
    {
        $user = User::factory()->create([
            'role_id' => getRoleIdBySlug('user'),
        ]);

        $response = $this->actingAsUser()->post('/api/customers', [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->email(),
            'phone_mobile' => fake()->phoneNumber(),
            'phone_home' => fake()->phoneNumber(),
            'address_1' => fake()->address(),
            'address_2' => fake()->address(),
            'address_3' => fake()->address(),
            'city' => fake()->city(),
            'state' => fake()->state(),
            'zipcode' => fake()->postcode(),
            'country' => 'Malaysia',
        ]);

        $response->assertStatus(403);
    }

    public function testGetAllCustomersAsAdmin()
    {
        $response = $this->actingAsAdmin()->get('/api/customers');
        $response->assertStatus(200);
    }

    public function testGetAllCustomersAsUser()
    {
        $response = $this->actingAsUser()->get('/api/customers');
        $response->assertStatus(403);
    }

    public function testUpdateCustomerAsAdmin()
    {
        $customer = Customer::factory()->create();
        $response = $this->actingAsAdmin()->put('/api/customers/' . $customer->id, [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
        ]);

        $response->assertStatus(200);
    }

    public function testUpdateCustomerAsUser()
    {
        $customer = Customer::factory()->create();
        $response = $this->actingAsUser()->put('/api/customers/' . $customer->id, [
            'first_name' => fake()->firstName(),
        ]);

        $response->assertStatus(403);
    }

    public function testUpdateCustomerOwnData()
    {
        $customer = Customer::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAsUser()->put('/api/customers/' . $customer->id, [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
        ]);

        $response->assertStatus(200);
    }
}

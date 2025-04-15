<?php

namespace Tests\Unit;

use App\Models\Charger;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ChargerTest extends TestCase
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

    public function testCreateCharger()
    {
        $charger = Charger::factory()->create();

        $this->assertDatabaseHas('chargers', [
            'name' => $charger->name,
            'description' => $charger->description,
            'isActive' => $charger->isActive,
        ]);
    }

    public function testCreateChargerAsAdmin()
    {
        $user = User::factory()->create([
            'role_id' => getRoleIdBySlug('admin'),
        ]);

        $response = $this->actingAsAdmin()->post('/api/chargers', [
            'name' => fake()->word() . ' Charger',
            'description' => fake()->sentence(),
            'isActive' => true,
        ]);

        $response->assertStatus(201);
    }

    public function testUpdateChargerAsAdmin()
    {
        $user = User::factory()->create([
            'role_id' => getRoleIdBySlug('admin'),
        ]);

        $charger = Charger::factory()->create();

        $response = $this->actingAsAdmin()->put('/api/chargers/' . $charger->id, [
            'name' => fake()->word() . ' Charger',
            'description' => fake()->sentence(),
            'isActive' => true,
        ]);

        $response->assertStatus(200);
    }

    public function testCreateChargerAsAdminWithInvalidData()
    {
        $user = User::factory()->create([
            'role_id' => getRoleIdBySlug('admin'),
        ]);

        $response = $this->actingAsAdmin()->post('/api/chargers', [
            'name' => '',
        ]);

        $response->assertStatus(422);
    }

    public function testGetAllChargersAsAdmin()
    {
        Charger::factory()->count(3)->create();

        $user = User::factory()->create([
            'role_id' => getRoleIdBySlug('admin'),
        ]);

        $response = $this->actingAsAdmin()->get('/api/chargers');

        $response->assertStatus(200);
    }

    public function testGetAllChargersAsUser()
    {
        $user = User::factory()->create();

        $response = $this->actingAsUser()->get('/api/chargers');

        $response->assertStatus(403);
    }

    public function testGetSingleChargerByIdAsAdmin()
    {
        $user = User::factory()->create([
            'role_id' => getRoleIdBySlug('admin'),
        ]);

        $charger = Charger::factory()->create();

        $response = $this->actingAsAdmin()->get('/api/chargers/' . $charger->id);

        $response->assertStatus(200);
    }

    public function testGetSingleChargerByIdAsUser()
    {
        $user = User::factory()->create();

        $charger = Charger::factory()->create();

        $response = $this->actingAsUser()->get('/api/chargers/' . $charger->id);

        $response->assertStatus(403);
    }

    public function testCreateChargerAsUser()
    {
        $user = User::factory()->create();

        $response = $this->actingAsUser()->post('/api/chargers', [
            'name' => 'Test Charger',
        ]);

        $response->assertStatus(403);
    }

    public function testUpdateChargerAsUser()
    {
        $user = User::factory()->create();

        $charger = Charger::factory()->create();

        $response = $this->actingAsUser()->put('/api/chargers/' . $charger->id, [
            'name' => 'Updated Charger',
        ]);

        $response->assertStatus(403);
    }

    public function testDeleteChargerAsAdmin()
    {
        $user = User::factory()->create([
            'role_id' => getRoleIdBySlug('admin'),
        ]);

        $charger = Charger::factory()->create();

        $response = $this->actingAsAdmin()->delete('/api/chargers/' . $charger->id);

        $response->assertStatus(200);
    }

    public function testDeleteChargerAsUser()
    {
        $user = User::factory()->create();

        $charger = Charger::factory()->create();

        $response = $this->actingAsUser()->delete('/api/chargers/' . $charger->id);

        $response->assertStatus(403);
    }
}

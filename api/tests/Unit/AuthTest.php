<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Str;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withHeaders(['Accept' => 'application/json']);
    }

    public function testCreateUser()
    {
        $user = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public function testRegisterUser()
    {
        $response = $this->post('/api/auth/register', [
            'name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'password' => Str::random(10),
        ]);

        $response->assertStatus(201);
    }

    public function testRegisterUserWithInvalidData()
    {
        $response = $this->post('/api/auth/register', [
            'name' => fake()->name(),
        ]);

        $response->assertStatus(422);
    }

    public function testLoginUser()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }

    public function testLoginUserWithInvalidCredentials()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => Str::random(10),
        ]);

        $response->assertStatus(401);
    }

    public function testLoginUserWithUnverifiedEmail()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->post('/api/auth/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(401);
    }

    public function testVerifyUser()
    {
        $user = User::factory()->create();

        $response = $this->post('/api/auth/verify', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
    }
}

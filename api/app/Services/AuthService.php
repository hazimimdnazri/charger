<?php

namespace App\Services;

use App\Exceptions\UserException;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthService
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function register(array $request): JsonResponse
    {
        $user = $this->user->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        return response()->json($user, 201);
    }

    public function login(Request $request): JsonResponse|UserException
    {
        $user = $this->user->where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw new UserException('Invalid credentialsz', 401);
        }

        if ($user->email_verified_at === null) {
            throw new UserException('Email not verified', 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
            'role' => $user->role->slug,
            'customer_id' => $user->customer->id ?? null,
            'charger_id' => $user?->customer?->customerChargers->first()?->id ?? null,
        ], 200);
    }

    public function verify(Request $request): JsonResponse|UserException
    {
        $user = $this->user->where('email', $request->email)->first();

        if (! $user) {
            throw new UserException('User not found');
        }

        if ($user->email_verified_at !== null) {
            return response()->json([
                'message' => 'Email already verified',
            ], 200);
        }

        $user->update(['email_verified_at' => now()]);

        return response()->json([
            'message' => 'Email verified successfully',
        ], 200);
    }

    public function logout(Request $request): JsonResponse
    {
        Log::debug($request->user());
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Logout successful',
        ], 200);
    }
}

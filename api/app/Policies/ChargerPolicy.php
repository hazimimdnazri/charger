<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class ChargerPolicy
{
    protected $unauthorizedMessage;

    protected $user;

    public function __construct()
    {
        $this->unauthorizedMessage = 'This action is not authorized';
    }

    private function isAdmin(User $user): bool
    {
        return $user->role_id === getRoleIdBySlug('admin');
    }

    public function before(User $user): ?Response
    {
        if ($this->isAdmin($user)) {
            return Response::allow();
        }

        return null;
    }

    public function viewAny(): Response
    {
        return Response::deny($this->unauthorizedMessage);
    }

    public function view(): Response
    {
        return Response::deny($this->unauthorizedMessage);
    }

    public function create(): Response
    {
        return Response::deny($this->unauthorizedMessage);
    }

    public function update(): Response
    {
        return Response::deny($this->unauthorizedMessage);
    }

    public function delete(): Response
    {
        return Response::deny($this->unauthorizedMessage);
    }

    public function forceDelete(): Response
    {
        return Response::deny($this->unauthorizedMessage);
    }
}

<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Requests\Api\V1\ReplaceUserRequest;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Policies\V1\UserPolicy;
use App\Traits\ApiResponses;


class UserController extends ApiController
{
    use ApiResponses;

    protected $policyClass = UserPolicy::class;


    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(User::filter($filters)->paginate());
    }


    public function store(StoreUserRequest $request)
    {
        if (! $this->isAble('store', User::class)) {
            return $this->unauthorized("You are not authorized to create a user!");
        }

        return new UserResource(User::create($request->mappedAttributes()));
    }


    public function show(User $user)
    {
        if ($this->include('tickets')) {
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }


    public function replace(ReplaceUserRequest $request, User $user)
    {
        if (! $this->isAble('replace', User::class)) {
            return $this->unauthorized("You are not authorized to replace this user!");
        }

        return new UserResource(tap($user, fn() => $user->update($request->mappedAttributes())));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if (! $this->isAble('update', User::class)) {
            return $this->unauthorized("You are not authorized to update this user!");
        }

        return new UserResource(tap($user, fn() => $user->update($request->mappedAttributes())));
    }

    public function destroy(User $user)
    {
        if (! $this->isAble('destroy', User::class)) {
            return $this->unauthorized("You are not authorized to delete this user!");
        }

        $user->delete();
        return $this->ok('User deleted!');
    }
}

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
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     */

    protected $policyClass = UserPolicy::class;

    use ApiResponses;
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(User::filter($filters)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        try {
            $this->isAble('store', User::class);
        } catch (AuthorizationException) {
            return $this->error("You are not authorized to create a user!", 401);
        }
        return new UserResource(User::create($request->mappedAttributes()));
    }

    /**
     * Display the specified resource.
     */
    public function show($user_id)
    {
        try {
            $user = User::findOrfail($user_id);
        } catch (ModelNotFoundException) {
            return $this->ok('User not found!');
        }

        if($this->include('tickets')) {
            return new UserResource($user->load('tickets'));
        }

        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function replace(ReplaceUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->isAble('replace', User::class);
        } catch(ModelNotFoundException){
            return $this->ok('User not found!');
        } catch (AuthorizationException) {
            return $this->error("You are not authorized to replace this user!", 401);
        }
        return new UserResource(tap($user, fn() => $user->update($request->mappedAttributes())));
    }
    public function update(UpdateUserRequest $request, $user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->isAble('update', User::class);
        } catch(ModelNotFoundException){
            return $this->ok('User not found!');
        } catch (AuthorizationException) {
            return $this->error("You are not authorized to update this user!", 401);
        }
        return new UserResource(tap($user, fn() => $user->update($request->mappedAttributes())));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($user_id)
    {
        try {
            $user = User::findOrFail($user_id);
            $this->isAble('destroy', User::class);
        } catch(ModelNotFoundException){
            return $this->ok('User not found!');
        } catch (AuthorizationException) {
            return $this->error("You are not authorized to delete this user!", 401);
        }
        $user->delete();
        return $this->ok('User deleted!');
    }
}

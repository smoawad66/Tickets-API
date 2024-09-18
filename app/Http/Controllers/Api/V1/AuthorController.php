<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Models\User;
use App\Http\Requests\Api\V1\StoreUserRequest;
use App\Http\Requests\Api\V1\UpdateUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorController extends ApiController
{
    /**
     * Display a listing of the resource.
     */

    use ApiResponses;
    public function index(AuthorFilter $filters)
    {
        return UserResource::collection(
            User::select('users.*')
            ->join('tickets', 'users.id', '=', 'tickets.user_id')
            ->filter($filters)->distinct()->paginate()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($author_id)
    {
        try {
            $author = User::findOrfail($author_id);
        } catch (ModelNotFoundException) {
            return $this->ok('User not found!');
        }

        if($this->include('tickets')) {
            return new UserResource($author->load('tickets'));
        }

        return new UserResource($author);
    }
}

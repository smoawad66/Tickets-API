<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\AuthorTicketsPolicy;
use App\Policies\V1\TicketPolicy;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Gate;

class AuthorTicketsController extends ApiController
{
    use ApiResponses;

    protected $policyClass = TicketPolicy::class;

    public function index($author_id, TicketFilter $filters)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $author_id)
                ->filter($filters)
                ->paginate()
        );
    }

    public function store($author_id, StoreTicketRequest $request)
    {
        try {
            User::findOrfail($author_id);
            $this->isAble('store', Ticket::class);
        } catch (ModelNotFoundException) {
            return $this->ok('User not found!');
        } catch (AuthorizationException) {
            return $this->error("You are not authorized to create a ticket for other users!", 401);
        }

        $model = $request->mappedAttributes();
        $model['user_id'] = (int) $author_id;
        return new TicketResource(Ticket::create($model));
    }

    public function replace($author_id, $ticket_id, ReplaceTicketRequest $request)
    {
        try {
            $ticket = Ticket::where([
                'id' => $ticket_id,
                'user_id' => $author_id
            ])->firstOrFail();

            $this->isAble('replace', $ticket);
        } catch (ModelNotFoundException) {
            return $this->error("Ticket cannot be found!", 404);
        } catch (AuthorizationException) {
            return $this->error("You are not authorized to replace this ticket!", 401);
        }
        $model = $request->mappedAttributes();

        $model['user_id'] ??= (int) $author_id;
        $ticket->update($model);

        return new TicketResource($ticket);
    }

    public function update($author_id, $ticket_id, UpdateTicketRequest $request)
    {
        try {
            $ticket = Ticket::where([
                'id' => $ticket_id,
                'user_id' => $author_id
            ])->firstOrFail();

            $this->isAble('update', $ticket);

        } catch (ModelNotFoundException) {
            return $this->error("Ticket cannot be found!", 404);
        } catch (AuthorizationException) {
            return $this->error("You are not authorized to update this ticket!", 401);
        }
        $attributes = $request->mappedAttributes();
        $ticket->update($attributes);
        return new TicketResource($ticket);
    }

    public function destroy($author_id, $ticket_id)
    {
        try {
            $ticket = Ticket::where([
                'id' => $ticket_id,
                'user_id' => $author_id
            ])->firstOrFail();

            $this->isAble('destroy', $ticket);
        } catch (ModelNotFoundException) {
            return $this->error("Ticket cannot be found!", 404);
        } catch (AuthorizationException) {
            return $this->error("You are not authorized to delete this ticket!", 401);
        }
        $ticket->delete();
        return $this->ok('Ticket deleted!');
    }
}

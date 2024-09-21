<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Policies\V1\TicketPolicy;
use App\Traits\ApiResponses;

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

    public function store(User $author, StoreTicketRequest $request)
    {
        if (! $this->isAble('store', Ticket::class)) {
            return $this->unauthorized("You are not authorized to create a ticket for other users!");
        }

        $attributes = $request->mappedAttributes();
        $attributes['user_id'] = $author->id;
        return new TicketResource(Ticket::create($attributes));
    }

    public function replace(ReplaceTicketRequest $request, $author_id, $ticket_id)
    {
        $ticket = Ticket::where(['id' => $ticket_id, 'user_id' => $author_id])->firstOrFail();

        if (! $this->isAble('replace', $ticket)) {
            return $this->unauthorized("You are not authorized to replace this ticket!");
        }

        $attributes = $request->mappedAttributes();
        $attributes['user_id'] ??= (int) $author_id;

        $ticket->update($attributes);
        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, $author_id, $ticket_id)
    {
        $ticket = Ticket::where(['id' => $ticket_id, 'user_id' => $author_id])->firstOrFail();

        if (! $this->isAble('update', $ticket)) {
            return $this->unauthorized("You are not authorized to update this ticket!");
        }

        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    public function destroy($author_id, $ticket_id)
    {
        $ticket = Ticket::where(['id' => $ticket_id, 'user_id' => $author_id])->firstOrFail();

        if (! $this->isAble('destroy', $ticket)) {
            return $this->unauthorized("You are not authorized to delete this ticket!");
        }

        $ticket->delete();
        return $this->ok('Ticket deleted!');
    }
}

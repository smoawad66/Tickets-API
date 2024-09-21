<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Models\Ticket;
use App\Http\Resources\V1\TicketResource;
use App\Policies\V1\TicketPolicy;
use App\Traits\ApiResponses;

class TicketController extends ApiController
{
    use ApiResponses;

    protected $policyClass = TicketPolicy::class;

    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }

    public function show(Ticket $ticket)
    {
        if ($this->include('author')) {
            return new TicketResource($ticket->load('author'));
        }

        return new TicketResource($ticket);
    }

    public function store(StoreTicketRequest $request)
    {
        if (! $this->isAble('store', Ticket::class)) {
            return $this->unauthorized("You are not authorized to create this ticket!");
        }

        return new TicketResource(Ticket::create($request->mappedAttributes()));
    }

    public function replace(ReplaceTicketRequest $request, Ticket $ticket)
    {
        if (! $this->isAble('replace', $ticket)) {
            return $this->unauthorized("You are not authorized to replace this ticket!");
        }

        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        if (! $this->isAble('update', $ticket)) {
            return $this->unauthorized("You are not authorized to update this ticket!");
        }

        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    public function destroy(Ticket $ticket)
    {
        if (! $this->isAble('destroy', $ticket)) {
            return $this->unauthorized("You are not authorized to delete this ticket!");
        }
        $ticket->delete();
        return $this->ok('Ticket deleted!');
    }
}

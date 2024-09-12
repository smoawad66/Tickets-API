<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Models\Ticket;
use App\Http\Resources\V1\TicketResource;
use App\Traits\ApiChecks;
use App\Traits\ApiResponses;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiChecks, ApiResponses;
    public function index(TicketFilter $filters)
    {
        return TicketResource::collection(Ticket::filter($filters)->paginate());
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTicketRequest $request)
    {
        $newTicket = $request['data.attributes'];
        $userId = $request['data.relationships.author.data.id'];
        $newTicket['user_id'] = $userId;
        return new TicketResource(Ticket::create($newTicket));
    }

    /**
     * Display the specified resource.
     */
    public function show($ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
        } catch (ModelNotFoundException) {
            return $this->error("Ticket can't be found!", 404);
        }

        if ($this->include('author')) {
            return new TicketResource($ticket->load('author'));
        }

        return new TicketResource($ticket);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ticketId)
    {
        try {
            Ticket::findOrFail($ticketId)->delete();
        } catch (ModelNotFoundException) {
            return $this->error("Ticket can't be found!", 404);
        }

        return $this->ok('Ticket deleted!');
    }
}

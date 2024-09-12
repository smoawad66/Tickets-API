<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\ApiResponses;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuthorTicketsController extends Controller
{
    use ApiResponses;
    public function index($authorId, TicketFilter $filters)
    {
        return TicketResource::collection(
            Ticket::where('user_id', $authorId)
                ->filter($filters)
                ->paginate()
        );
    }

    public function store($authorId, StoreTicketRequest $request)
    {
        try {
            User::findOrfail($authorId);
        } catch (ModelNotFoundException) {
            return $this->ok('User not found!');
        }
        $newTicket = Ticket::create($request->mappedAttributes());
        return new TicketResource($newTicket);
    }

    public function replace($authorId, $ticketId, ReplaceTicketRequest $request)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            if ($ticket->user_id != $authorId) {
                return $this->error("You are not authorized to update this ticket!", 403);
            }
        } catch (ModelNotFoundException) {
            return $this->error("Ticket cannot be found!", 404);
        }

        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    public function update($authorId, $ticketId, UpdateTicketRequest $request)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            if ($ticket->user_id != $authorId) {
                return $this->error("You are not authorized to update this ticket!", 403);
            }
        } catch (ModelNotFoundException) {
            return $this->error("Ticket cannot be found!", 404);
        }

        $ticket->update($request->mappedAttributes());
        return new TicketResource($ticket);
    }

    public function destroy($authorId, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            if ($ticket->user_id != $authorId) {
                return $this->error("You are not authorized to delete this ticket!", 403);
            }
        } catch (ModelNotFoundException) {
            return $this->error("Ticket cannot be found!", 404);
        }

        $ticket->delete();
        return $this->ok('Ticket deleted!');
    }
}

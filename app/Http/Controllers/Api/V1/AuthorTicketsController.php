<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Resources\V1\TicketResource;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\ApiResponses;
use Exception;
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
        $newTicket = array_merge($request['data.attributes'], ['user_id' => $authorId]);
        return new TicketResource(Ticket::create($newTicket));
    }
    public function destroy($authorId, $ticketId)
    {
        $ticket = Ticket::firstWhere([
            'id' => $ticketId,
            'user_id' => $authorId,
        ]);

        if ($ticket == null) {
            return $this->error("Ticket cannot be found!", 404);
        }

        $ticket->delete();
        return $this->ok('Ticket deleted!');
    }
}

<?php

namespace App\Http\Filters\V1;

use App\Models\Ticket;

class TicketFilter extends QueryFilter {

    protected $sortable = [
        'id',
        'title',
        'status',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function include($value) {
        if(method_exists(Ticket::class, $value)) {
            $this->builder->with($value);
        }
    }

    public function status($value) {
        $this->builder->whereIn('status', explode(',', $value));
    }

    public function title($value) {
        $value = str_replace('*', '%', $value);
        $this->builder->whereLike('title', $value);
    }
}


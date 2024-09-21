<?php

namespace App\Models;

use App\Http\Filters\V1\TicketFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'status',
        'description',
        'user_id',
    ];

    public function author() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeFilter(Builder $builder, TicketFilter $filters) {
        return $filters->apply($builder);
    }
}

<?php

namespace App\Http\Filters\V1;

use App\Models\User;

class AuthorFilter extends QueryFilter {

    protected $sortable = [
        'id',
        'name',
        'email',
        'createdAt' => 'created_at',
        'updatedAt' => 'updated_at',
    ];

    public function include($value) {
        if(method_exists(User::class, $value)) {
            $this->builder->with($value);
        }
    }

    public function name($value) {
        $value = str_replace('*', '%', $value);
        $this->builder->whereLike('name', $value);
    }

    public function email($value) {
        $value = str_replace('*', '%', $value);
        $this->builder->whereLike('email', $value);
    }
}


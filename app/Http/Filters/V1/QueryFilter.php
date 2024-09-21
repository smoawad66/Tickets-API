<?php

namespace App\Http\Filters\V1;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class QueryFilter {

    public $request;
    protected $builder;

    protected $sortable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply(Builder $builder) {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
        return $builder;
    }

    protected function filter(array $arr) {
        foreach ($arr as $key => $value) {
            if (method_exists($this, $key)) {
                $this->$key($value);
            }
        }
    }


    protected function sort($value) {
        $params = explode(',', $value);
        foreach ($params as $param) {
            $direction = 'asc';
            if ($param[0] == '-') {
                $direction = 'desc';
                $param = substr($param, 1);
            }

            if(!in_array($param, $this->sortable) && !isset($this->sortable[$param])) {
                continue;
            }

            $columnName = $this->sortable[$param] ?? $param;

            $this->builder->orderBy($columnName, $direction);
        }
    }

}


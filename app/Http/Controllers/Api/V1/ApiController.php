<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class ApiController extends Controller
{
    protected $policyClass = null;

    protected function include(string $relationship): bool
    {
        $param = strtolower(request('include'));
        $includedValues = explode(',', $param);
        return in_array(strtolower($relationship), $includedValues);
    }

    protected function isAble($ability, $targetModel, $author_id = null)
    {
        return Gate::authorize($ability, [$targetModel, $author_id, $this->policyClass]);
    }
}

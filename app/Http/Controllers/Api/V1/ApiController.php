<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;
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

    protected function isAble($ability, $targetModel)
    {
        try {
            Gate::authorize($ability, [$targetModel, $this->policyClass]);
            return true;
        } catch (AuthorizationException) {
            return false;
        }
    }
}

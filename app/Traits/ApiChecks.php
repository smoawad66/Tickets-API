<?php

namespace App\Traits;

trait ApiChecks
{
    protected function include(string $relationship): bool
    {
        $param = strtolower(request('include'));
        $includedValues = explode(',', $param);
        return in_array(strtolower($relationship), $includedValues);
    }
}

<?php

namespace App\Traits;

use App\Models\User;
use App\Permissions\V1\Abilities;

trait ApiResponses
{

    protected function ok($message, $data = [])
    {
        return $this->success($message, $data, 200);
    }

    protected function success($message, $data = [], $statusCode = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data,
            'status' => $statusCode
        ], $statusCode);
    }

    protected function error($errors, $status_code = 200)
    {
        if (is_string($errors)) {
            return response()->json([
                'message' => $errors,
                'status' => $status_code
            ], $status_code);
        }

        return response()->json([
            'errors' => $errors
        ], $status_code);
    }

    protected function unauthorized($message)
    {
        return $this->error([
            [
                'status' => 401,
                'message' => $message,
                'source' => '',
            ]
        ]);
    }
}

<?php

namespace App\Exceptions;

use Exception;
use App\Traits\ApiResponses;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ApiExceptionsHandler
{
    use ApiResponses;

    private $handlers = [
        NotFoundHttpException::class => 'handleNotFoundHttp',
        ModelNotFoundException::class => 'handleModelNotFound',
        AuthenticationException::class => 'handleAuthentication',
        ValidationException::class => 'handleValidation',
    ];

    public function handle(Exception $e)
    {
        $class_name = get_class($e);
        $method = $this->handlers[$class_name] ?? null;

        if ($method ?? false) {
            return $this->error($this->$method($e));
        }

        return $this->error([
            [
                'type' => substr($class_name, strrpos($class_name, '\\')),
                'status' => 0,
                'message' => $e->getMessage(),
                'source' => '',
            ]
        ]);
    }


    public function handleNotFoundHttp(NotFoundHttpException $e)
    {
        return [
            [
                'status' => 404,
                'message' => 'Resource cannot be found!',
                'source' => $e->getMessage(),
            ],
        ];
    }

    public function handleModelNotFound(ModelNotFoundException $e)
    {
        return [
            [
                'status' => 404,
                'message' => 'Resource cannot be found!',
                'source' => $e->getModel(),
            ],
        ];
    }


    public function handleValidation(ValidationException $e)
    {
        foreach ($e->errors() as $key => $value) {
            $errors[] = [
                'status' => 422,
                'message' => $value[0],
                'source' => $key,
            ];
        }
        return $errors;
    }

    public function handleAuthentication(AuthenticationException $e)
    {
        return [
            [
                'status' => 401,
                'message' => $e->getMessage(),
                'source' => '',
            ],
        ];
    }
}

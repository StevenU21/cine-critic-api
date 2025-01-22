<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class NotFoundException extends Exception
{
    protected $message;

    public function __construct($message = 'Resource does not exist')
    {
        parent::__construct($message);
        $this->message = $message;
    }

    public function render($request): JsonResponse
    {
        return response()->json([
            'message' => $this->message,
        ], 404);
    }
}

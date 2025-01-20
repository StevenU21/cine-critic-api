<?php

namespace App\Exceptions;

use Exception;

class NotAuthenticatedException extends Exception
{
    public function render($request)
    {
        return response()->json([
            'message' => 'You are not authenticated',
        ], 401);
    }
}

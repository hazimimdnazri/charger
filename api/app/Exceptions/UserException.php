<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception
{
    public function __construct(string $message, int $code = 500)
    {
        parent::__construct($message);
        $this->code = $code;
    }

    public function render()
    {
        return response()->json([
            'message' => $this->getMessage(),
        ], $this->code);
    }
}

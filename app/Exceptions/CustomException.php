<?php

namespace App\Exceptions;

use App\Traits\SendApiResponse;
use Exception;
use Throwable;

class CustomException extends Exception
{
    use SendApiResponse;

    protected $message, $status;
    public function __construct($message, $status = 500)
    {
        $this->message = $message;
        $this->status = $status;
    }

    public function getStatus() {
        return $this->status;
    }

    public function render($request, Throwable $e) {
        return $this->sendApiResponse($e->getMessage(), $this->status);
    }
}

<?php

namespace App\Traits;

trait SendApiResponse
{
    public function sendApiResponse($message, $responseCode, $data = null) {
        return response()->json(
            [
                "message" => $message,
                "data" => $data
            ],
            $responseCode
        );
    }
}

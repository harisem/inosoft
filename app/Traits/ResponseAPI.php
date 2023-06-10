<?php

namespace App\Traits;

use Tymon\JWTAuth\Facades\JWTAuth;

trait ResponseAPI
{
    public function coreResponse($message, $statusCode, $data = null, $isSuccess = true, $token = null)
    {
        if ($isSuccess) {
            if ($token !== null) {
                return response()->json([
                    'message' => $message,
                    'access_token' => $token,
                    'expires_in' => JWTAuth::factory()->getTTL() * 60,
                ], $statusCode);
            } else {
                $payload = [
                    'status_code' => $statusCode,
                    'message' => $message,
                ];

                if ($data) $payload['data'] = $data;

                return response()->json($payload, $statusCode);
            }  
        } else {
            return response()->json([
                'status_code' => $statusCode,
                'message' => $message,
            ], $statusCode);
        }
    }

    public function success($message, $data, $statusCode = 200)
    {
        return $this->coreResponse($message, $statusCode, $data);
    }

    public function error($message, $statusCode = 500)
    {
        return $this->coreResponse($message, $statusCode, null, false);
    }

    public function respondWithToken($message, $accessToken)
    {
        return $this->coreResponse($message, 200, null, true, $accessToken);
    }
}
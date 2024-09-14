<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

abstract class Controller
{
    /**
     * Success response method.
     *
     * @param $response_data
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($response_data, $message, int $code = Response::HTTP_OK) : JsonResponse
    {
        $response = [
            'success' => true,
            'data'    => $response_data,
            'message' => $message,
        ];
        return response()->json($response, $code);
    }

    /**
     * Error response method.
     *
     * @param string $error_message
     * @param array $error_details
     * @param int $code
     * @return JsonResponse
     */
    public function sendError(string $error_message, array $error_details = [], int $code = Response::HTTP_NOT_FOUND): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error_message,
        ];

        if(!empty($error_details)){
            $response['details'] = $error_details;
        }
        return response()->json($response, $code);
    }
}

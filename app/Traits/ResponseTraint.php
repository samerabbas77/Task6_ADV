<?php
namespace App\Traits;

use Illuminate\Http\JsonResponse;



trait ResponseTraint
{
    /**
     * Generate a success response in JSON format
     * @param mixed $data
     * @param string $message
     * @param int $httpResponseCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function succeesTrait($data , string $message = 'Success', int $httpResponseCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => $data,
            'errors'  => null,
        ], $httpResponseCode);
    }
    

    /**
     * Generate a not found response in JSON format
     * @param string $message
     * @param int $httpResponseCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function faildTrait(string $message = 'faild', int $httpResponseCode = 404): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'code'    => $httpResponseCode,
        ], $httpResponseCode);
    
    }
}
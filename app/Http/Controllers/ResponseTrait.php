<?php
    namespace App\Http\Controllers;
    use Symfony\Component\HttpFoundation\JsonResponse;

    trait ResponseTrait
    {
        public function successResponse($data=[], $message='') : JsonResponse
        {
            return response()->json([
                'status' => 'true',
                'data' => $data,
                'message' => $message
            ]);
        }
        public function errorResponse($message='' ,$status) : JsonResponse
        {
            return response()->json([
                'status' => 'false',

                'message' => $message
            ],$status);
        }
    }

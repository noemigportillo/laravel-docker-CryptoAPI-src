<?php

namespace App\Infrastructure\Controllers;

use App\Apllicaction\BuyCoinService;

class BuyCoinController extends BaseController
{
    public function __invoke($BuyCoinFromRequest): JsonResponse
    {

        return response()->json([
            'status' => 'Ok',
            'message' => 'Request received and worked',
        ], Response::HTTP_OK);
    }
}

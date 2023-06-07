<?php

namespace App\Infrastructure\Controllers;

use App\Application\BuyCoinService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Barryvdh\Debugbar\Controllers\BaseController;

class BuyCoinController extends BaseController
{
    private BuyCoinService $buyCoinService;
    public function __construct(BuyCoinService $buyCoinService)
    {
        $this->buyCoinService = $buyCoinService;
    }

    public function __invoke(Request $bodyPetition): JsonResponse
    {
        try {
            $coin = $this->buyCoinService->execute(
                $bodyPetition->input("coin_id"),
                $bodyPetition->input("wallet_id"),
                $bodyPetition->input("amount_usd")
            );
        } catch (\Exception $ex) {
            return response()->json([
                'a coin with the specified ID was not found.'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'coin_id' => $coin->getId()
        ], Response::HTTP_OK);
    }
}

<?php

namespace App\Infrastructure\Controllers;

use App\Application\SellCoinService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SellCoinController extends BaseController
{
    private SellCoinService $sellCoinService;
    public function __construct(SellCoinService $sellCoinService)
    {
        $this->sellCoinService = $sellCoinService;
    }

    public function __invoke(Request $bodyPetition): JsonResponse
    {
        try {
            $this->sellCoinService->execute(
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
            'successful operation'
        ], Response::HTTP_OK);
    }
}

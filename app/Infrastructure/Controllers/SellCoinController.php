<?php

namespace App\Infrastructure\Controllers;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\SellCoin\SellCoinService;
use Illuminate\Routing\Controller as BaseController;
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
        } catch (WalletNotFoundException $e) {
            return response()->json([
                'A wallet with the specified ID was not found.',
            ], Response::HTTP_NOT_FOUND);
        } catch (CoinNotFoundException $e) {
            return response()->json([
                'A coin with the specified ID was not found.'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'successful operation'
        ], Response::HTTP_OK);
    }
}

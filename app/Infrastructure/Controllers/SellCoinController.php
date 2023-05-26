<?php

namespace App\Infrastructure\Controllers;

use App\Application\SellCoinService;
use Barryvdh\Debugbar\Controllers\BaseController;
use Barryvdh\Debugbar\LaravelDebugbar;
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

    public function __invoke(string $coin_id, string $wallet_id, float $amountUSD): JsonResponse
    {
        try {
            $this->sellCoinService->execute($coin_id, $wallet_id, $amountUSD);
            return response()->json([
                'status' => 'Ok',
                'message' => 'Venta de moneda realizada con éxito',
            ], Response::HTTP_OK);
        } catch (CoinNotFoundException $ex) {
            return response()->json([
                'a coin with the specified ID was not found.',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

<?php

namespace App\Infrastructure\Controllers;

use App\Application\Exceptions\BadRequestException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletCryptocurrenciesService;
use App\Application\WalletDataSource\WalletDataSource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetWalletCryptocurrenciesController extends BaseController
{
    /*public function __invoke(): JsonResponse
    {
        return response()->json([
            'status' => 'Ok',
            'message' => 'Systems are up and running',
        ], Response::HTTP_OK);
    }*/

    // private WalletDataSource $walletDataSource;
    private WalletCryptocurrenciesService $walletCryptocurrenciesService;

    /**
     * @param WalletCryptocurrenciesService $walletCryptocurrenciesService
     */
    public function __construct(WalletCryptocurrenciesService $walletCryptocurrenciesService)
    {
        $this->walletCryptocurrenciesService = $walletCryptocurrenciesService;
    }


    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $coins = $this->walletCryptocurrenciesService->execute($wallet_id);

            // Successful operation
            $publicCoins = array_map(function ($coin) {
                return [
                    'coin_id' => $coin->getId(),
                    'name' => $coin->getName(),
                    'symbol' => $coin->getSymbol(),
                    'amount' => $coin->getAmount(),
                    'value_usd' => $coin->getValueUsd(),
                ];
            }, $coins);

            return response()->json($publicCoins, Response::HTTP_OK);
        } catch (WalletNotFoundException $e) {
            return response()->json([
                'a wallet with the specified ID was not found.',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

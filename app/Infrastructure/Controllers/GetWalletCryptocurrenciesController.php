<?php

namespace App\Infrastructure\Controllers;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletCryptocurrencies\WalletCryptocurrenciesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class GetWalletCryptocurrenciesController extends BaseController
{
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
                'A wallet with the specified ID was not found.',
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

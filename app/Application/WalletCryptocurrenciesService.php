<?php

namespace App\Application;

// use App\Application\WalletDataSource

use App\Application\Exceptions\BadRequestException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\WalletCryptocurrenciesDataSource;
use http\Exception\BadConversionException;

class WalletCryptocurrenciesService
{
    private WalletDataSource $walletDataSource;

    /**
     * @param WalletDataSource $walletDataSource
     */
    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }


    public function execute(string $wallet_id): ?array
    {
        //$this->walletCryptocurrenciesDataSource = new WalletCryptocurrenciesDataSource();
        $wallet = $this->walletDataSource->getWalletInfo($wallet_id);
        if (is_null($wallet)) {
            throw new WalletNotFoundException();
        }

        return $wallet->getCoins();
    }

    /*public function __invoke(string $wallet_id): JsonResponse
    {
        $walletCryptocurrencies = $this->walletCryptocurrenciesService->execute($wallet_id);

        if (($walletCryptocurrencies)) {
            return response()->json([
                'Wallet Cryptocurrencies' => 'Existe',
                Response::HTTP_OK
            ]);
        }

        return response()->json([
            'Wallet Cryptocurrencies' => 'No existe',
            Response::HTTP_NOT_FOUND
        ]);
    }*/
}

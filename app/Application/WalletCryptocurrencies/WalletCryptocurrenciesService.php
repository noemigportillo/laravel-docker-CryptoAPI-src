<?php

namespace App\Application\WalletCryptocurrencies;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletDataSource\WalletDataSource;
use App\Infrastructure\Persistence\WalletCryptocurrenciesDataSource;

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


    /**
     * @throws WalletNotFoundException
     */
    public function execute(string $wallet_id): ?array
    {
        $wallet = $this->walletDataSource->getWalletInfo($wallet_id);
        if (is_null($wallet)) {
            throw new WalletNotFoundException();
        }

        return $wallet->getCoins();
    }
}

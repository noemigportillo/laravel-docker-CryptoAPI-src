<?php

namespace App\Application\BalanceWallet;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletDataSource\WalletDataSource;
use App\Infrastructure\Persistence\APIClient;

class BalanceWalletService
{
    private WalletDataSource $walletDataSource;
    private APIClient $apiClient;

    /**
     * @param WalletDataSource $walletDataSource
     * @param APIClient $apiClient
     */
    public function __construct(WalletDataSource $walletDataSource, APIClient $apiClient)
    {
        $this->walletDataSource = $walletDataSource;
        $this->apiClient = $apiClient;
    }


    public function execute(string $wallet_id): ?float
    {
        $wallet = $this->walletDataSource->getWalletInfo($wallet_id);
        if (is_null($wallet)) {
            throw new WalletNotFoundException();
        }
        $balance = 0;
        $coinsWallet = $wallet->getCoins();
        foreach ($coinsWallet as $coin) {
            $coinInfo = $this->apiClient->getCoinInfo($coin->getId());
            if (is_null($coinInfo)) {
                throw new CoinNotFoundException();
            }
            $balance = $balance + ($coinInfo->getValueUsd() * $coin->getAmount());
        }
        return $balance;
    }
}

<?php

namespace App\Application;

use App\Application\Exceptions\WalletNotFoundException;
use App\Infrastructure\Persistence\APICliente;
use App\Application\WalletDataSource\WalletDataSource;
use App\Application\CoinDataSource\CoinDataSource;
use App\Application\Exceptions\CoinNotFoundException;

class BalanceWalletService
{
    private WalletDataSource $walletDataSource;
    /**
     * @param WalletDataSource $walletDataSource
     */

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }


    public function execute(string $wallet_id): ?float
    {
        $api = new APICliente();
        $wallet = $this->walletDataSource->getWalletInfo($wallet_id);
        if (is_null($wallet)) {
            throw new WalletNotFoundException();
        }
        $balance = 0;
        $coinsWallet = $wallet->getCoins();
        foreach ($coinsWallet as $coin) {
            $coinInfo = $api->getCoinInfo($coin->getId());
            if (is_null($coinInfo)) {
                throw new CoinNotFoundException();
            }
            $balance = $balance + ($coinInfo->getValueUsd() * $coin->getAmount());
        }
        return $balance;
    }
}

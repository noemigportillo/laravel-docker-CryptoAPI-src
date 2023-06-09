<?php

namespace App\Application;

use App\Application\Exceptions\WalletNotFoundException;
use App\Domain\Wallet;
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
        $balance = $wallet->getBalanceUsd();
        $coinsWallet = $wallet->getCoins();
        foreach ($coinsWallet as $coin) {
            if (is_null($api->getCoinInfo($coin->getId()))) {
                return null;
            }
            $newValuesCoin = $api->getCoinInfo($coin->getId());
            $balance = $balance + ($newValuesCoin->getValueUsd() * $coin->getAmount());
        }
        return $balance;
    }
}

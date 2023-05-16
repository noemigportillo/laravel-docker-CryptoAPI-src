<?php

namespace App\Infrastructure\Persistence;

use App\Application\UserDataSource\UserDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;

class WalletCryptocurrenciesDataSource implements WalletDataSource
{
    public function getWalletInfo(string $wallet_id): Wallet
    {
        $coin = new Coin("coinId", "name", "symbol", 0.2, 0.3);
        $coins = array($coin);
        return new Wallet("userId", "walletId", $coins, 0.4);
        //  new Wallet(1, "email@email.com");
    }

    /*public function findByEmail(string $email): Wallet
    {
        return new Wallet(1, "email@email.com");
    }*/

    /*public function getAll(): array
    {
        return [new Wallet(1, "email@email.com"), new Wallet(2, "another_email@email.com")];
    }*/
}

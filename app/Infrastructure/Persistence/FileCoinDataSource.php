<?php

namespace App\Infrastructure\Persistence;

use App\Application\CoinDataSource\CoinDataSource;
use App\Domain\Coin;

class FileCoinDataSource implements CoinDataSource
{
    /*public function getCoinInfo(string $coin_id): Coin
    {
        return new Coin("coinId", "name", "symbol", 1.2, 1.3);
    }*/

    public function getCoinsWallet(string $coin_id): array
    {
        $coin1 = new Coin("coinId", "name", "symbol", 1.2, 1.3);
        $coin2 = new Coin("coinId2", "name2", "symbol2", 2.2, 2.3);
        $arrayCoins = [$coin1, $coin2];
        return $arrayCoins;
    }
}

<?php

namespace App\Application\CoinDataSource;

use App\Domain\Coin;

interface CoinDataSource
{
    public function getCoinInfo(string $coin_id): ?Coin;

    public function getCoinsWallet(): ?array;
}

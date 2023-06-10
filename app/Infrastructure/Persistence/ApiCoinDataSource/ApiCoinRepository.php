<?php

namespace App\Infrastructure\Persistence\ApiCoinDataSource;

use App\Domain\Coin;
use App\Infrastructure\Persistence\APIClient;

class ApiCoinRepository
{
    private Coin $coin;
    public function buySell(string $coinId, float $amountUSD): ?Coin
    {
        $api = new APIClient();
        if (is_null($api->getCoinInfo($coinId))) {
            return null;
        }
        $this->coin = $api->getCoinInfo($coinId);
        $priceCoinUsd = $this->coin->getValueUsd();
        $amountCoin = $amountUSD / $priceCoinUsd;
        $this->coin->setAmount($amountCoin);
        return $this->coin;
    }
}

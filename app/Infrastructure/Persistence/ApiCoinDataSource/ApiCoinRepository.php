<?php

namespace App\Infrastructure\Persistence\ApiCoinDataSource;

use App\Domain\Coin;
use App\Infrastructure\Persistence\APICliente;

class ApiCoinRepository
{
    private Coin $coin;
    public function buySell(string $coinId, float $amountUSD): ?Coin
    {
        $api =  new ApiCoinRepository();
        $this->coin = $api->getCoinInfo($coinId);
        $priceCoinUsd = $this->coin->getValueUsd();
        $amountCoin = $amountUSD / $priceCoinUsd;
        $this->coin->setAmount($amountCoin);
        return $this->coin;
    }
}

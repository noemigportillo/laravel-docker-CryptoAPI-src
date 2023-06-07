<?php

namespace App\Infrastructure\Persistence\ApiCoinDataSource;

use App\Domain\Coin;
use App\Infrastructure\Persistence\APICliente;

class ApiCoinRepository
{
    private Coin $coin;
    public function buySell(string $coinId, float $amount_usd): ?Coin
    {
        $api = new APICliente();
        if (is_null($api->getCoinInfo($coinId))) {
            return null;
        }
        $this->coin = $api->getCoinInfo($coinId);
        $priceCoinUsd = $this->coin->getValueUsd();
        $amountCoin = $amount_usd / $priceCoinUsd;
        $this->coin->setAmount($amountCoin);
        return $this->coin;
    }
}

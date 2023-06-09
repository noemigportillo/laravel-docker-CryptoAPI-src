<?php

namespace App\Infrastructure\Persistence\ApiCoinDataSource;

use App\Application\Exceptions\CoinNotFoundException;
use App\Domain\Coin;
use App\Infrastructure\Persistence\APICliente;

class ApiCoinRepository
{
    public function calculateAmountOfCoinWithAmountUsd(string $coinId, float $amount_usd): ?Coin
    {
        $api = new APICliente();
        $coin = $api->getCoinInfo($coinId);
        if (is_null($coin)) {
            throw new CoinNotFoundException();
        }
        $priceCoinUsd = $coin->getValueUsd();
        $amountCoin = $amount_usd / $priceCoinUsd;
        $coin->setAmount($amountCoin);
        return $coin;
    }
}

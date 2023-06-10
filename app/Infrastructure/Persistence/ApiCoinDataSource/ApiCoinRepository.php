<?php

namespace App\Infrastructure\Persistence\ApiCoinDataSource;

use App\Application\Exceptions\CoinNotFoundException;
use App\Domain\Coin;
use App\Infrastructure\Persistence\APIClient;

class ApiCoinRepository
{
    /**
     * @throws CoinNotFoundException
     */
    public function calculateAmountOfCoinWithAmountUsd(string $coin_id, float $amount_usd): ?Coin
    {
        $api = new APIClient();
        $coin = $api->getCoinInfo($coin_id);
        if (is_null($coin)) {
            throw new CoinNotFoundException();
        }
        $priceCoinUsd = $coin->getValueUsd();
        $amountCoin = $amount_usd / $priceCoinUsd;
        $coin->setAmount($amountCoin);
        return $coin;
    }
}

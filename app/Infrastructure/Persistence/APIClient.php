<?php

namespace App\Infrastructure\Persistence;

use App\Domain\Coin;

class APIClient
{
    public function getCoinInfo(string $coin_id): ?Coin
    {
        $url = 'https://api.coinlore.net/api/ticker/?id=' . $coin_id;
        $data = file_get_contents($url);
        $response = json_decode($data);
        if ($response) {
            $bitcoinData = $response[0];

            $idCoin = $bitcoinData->id;
            $symbol = $bitcoinData->symbol;
            $name = $bitcoinData->name;
            $price = $bitcoinData->price_usd;
            return new Coin($idCoin, $name, $symbol, 0, $price);
        }
        return null;
    }
}

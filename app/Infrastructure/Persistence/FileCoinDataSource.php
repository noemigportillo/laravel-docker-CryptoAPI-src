<?php

namespace App\Infrastructure\Persistence;

use App\Application\CoinDataSource\CoinDataSource;
use App\Domain\Coin;
use App\Infrastructure\Exceptions\CoinNotFoundException;

class FileCoinDataSource implements CoinDataSource
{
    public function getCoinInfo(string $coin_id): ?Coin
    {
        $url = 'https://api.coinlore.net/api/ticker/?id=' . $coin_id;
        $data = file_get_contents($url);
        $response = json_decode($data);
        if ($response && $response->data) {
            $bitcoinData = $response->data[0]; //Obtener el primer elemento de la matriz de datos

            //Acceder a los atributos específicos de Bitcoin
            $id_coin = $bitcoinData->id;
            $symbol = $bitcoinData->symbol;
            $name = $bitcoinData->name;
            $price = $bitcoinData->price_usd;
            return new Coin($id_coin, $name, $symbol, 1.2, $price);
        }
        throw new CoinNotFoundException();
    }

    public function getCoinsWallet(): array
    {
        //acceder a mi cartera y coger las moedas que tengo
        $coin1 = new Coin("90", "Bitcoin", "BTC", 1.2, 26721.88);
        $coin2 = new Coin("coinId2", "name2", "symbol2", 2.2, 2.3);
        $arrayCoins = [$coin1, $coin2];
        return $arrayCoins;
    }
}

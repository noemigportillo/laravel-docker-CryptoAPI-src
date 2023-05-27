<?php

namespace App\Infrastructure\Persistence;

use mysql_xdevapi\Exception;

class APICliente
{
    public function coinData($coinId): float
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.coinlore.net/api/ticker/?id=' . $coinId,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));
        $response = curl_exec($curl);
        if (!$response) {
            throw new Exception('Coin Not Found');
        }
        $information = json_decode($response);
        return $information->{'data'}->{'price_usd'};
    }
}

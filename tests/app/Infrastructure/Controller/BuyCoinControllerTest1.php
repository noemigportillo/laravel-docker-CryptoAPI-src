<?php

namespace Tests\app\Infrastructure\Controller;

use BuyCoinControllerTest;
use App\Infrastructure\Persistence\APICliente;
use PHPUnit\Framework\TestCase;
use App\Domain\Coin;

class BuyCoinControllerTest1 extends TestCase
{
    /**
     * @test
     */
    public function checkIfCorrectInfoOfCoinInApi()
    {
        $api =  new APICliente();
        $result = $api->getCoinInfo(90);
        $coin = new Coin('90', "Bitcoin", "BTC", 0, 26721.88);

        $this->assertEquals($coin->getName(), $result->getName());
    }
}

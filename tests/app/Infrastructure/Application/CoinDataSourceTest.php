<?php

namespace Tests\app\Infrastructure\Application;

use PHPUnit\Framework\TestCase;
use Mockery;
use App\Application\CoinDataSource\CoinDataSource;
use App\Application\SellCoinService;
use App\Infrastructure\Exceptions\CoinNotFoundException;
use App\Domain\Coin;

class CoinDataSourceTest extends TestCase
{
    private CoinDataSource $coinDataSource;
    private SellCoinService $sellCoinService;

    protected function setUp(): void
    {
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->sellCoinService = new SellCoinService($this->coinDataSource);
    }
    /**
     * @test
     */
    public function getCoin()
    {
        $coin1 = new Coin("90", "Bitcoin", "BTC", 1.2, 26721.88);
        $coin2 = new Coin("coinId2", "name2", "symbol2", 2.2, 2.3);
        $arrayCoins = [$coin1, $coin2];

        $this->coinDataSource
            ->expects('getCoinsWallet')
            ->with()
            ->andReturn($arrayCoins);

        $this->coinDataSource
            ->expects('getCoinInfo')
            ->with("90")
            ->andReturn($coin1);


        $result_coin = $this->sellCoinService->execute(90, "wallet_id", "amount_usd");
        $this->assertEquals($coin1, $result_coin);
    }
    /**
     * @test
     */
    public function getCoinException()
    {
        $coin1 = new Coin("90", "Bitcoin", "BTC", 1.2, 26721.88);
        $coin2 = new Coin("coinId2", "name2", "symbol2", 2.2, 2.3);
        $arrayCoins = [$coin1, $coin2];

        $this->coinDataSource
            ->expects('getCoinsWallet')
            ->with()
            ->andReturn($arrayCoins);

        $this->coinDataSource
            ->expects('getCoinInfo')
            ->with("80")
            ->andReturn($coin1);


        $this->expectException(CoinNotFoundException::class);
        $this->sellCoinService->execute(80, "wallet_id", "amount_usd");
    }
}

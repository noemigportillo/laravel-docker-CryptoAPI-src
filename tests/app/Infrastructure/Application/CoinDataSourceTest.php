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
    public function getCoinWallet()
    {
        $coin1 = new Coin("coinId", "name", "symbol", 1.2, 1.3);
        $coin2 = new Coin("coinId2", "name2", "symbol2", 2.2, 2.3);
        $arrayCoins = [$coin1, $coin2];

        $this->coinDataSource
            ->expects('getCoinsWallet')
            ->with("coinId")
            ->andReturn($arrayCoins);

        $result_coin = $this->sellCoinService->execute("coinId");
        $this->assertEquals($coin1, $result_coin);
    }
    /**
     * @test
     */
    public function getCoinWalletException()
    {
        $coin1 = new Coin("coinId", "name", "symbol", 1.2, 1.3);
        $coin2 = new Coin("coinId2", "name2", "symbol2", 2.2, 2.3);
        $coin3 = new Coin("coinId3", "name2", "symbol2", 2.2, 2.3);
        $arrayCoins = [$coin1, $coin2];

        $this->coinDataSource
            ->expects('getCoinsWallet')
            ->with("coinId3")
            ->andReturn($arrayCoins);

        $this->expectException(CoinNotFoundException::class);
        $this->sellCoinService->execute("coinId3");
    }
}

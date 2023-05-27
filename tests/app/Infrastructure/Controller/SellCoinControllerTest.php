<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\UserDataSource\UserDataSource;
use App\Application\SellCoinService;
use App\Application\CoinDataSource\CoinDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    private CoinDataSource $coinDataSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(coinDataSource::class, function () {
            return $this->coinDataSource;
        });
    }
    /**
     * @test
     */
    public function coinNotFound()
    {
        $this->coinDataSource
            ->expects('getCoinInfo')
            ->with('coin_id')
            ->andReturn(null);

        $response = $this->post('/api/coin/sell', ['coin_id' => 'coin_id']);

        $response->assertNotFound();
        $response->assertExactJson(['a coin with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function searchCoinAndReturnsOk()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 1.2, 26721.88);
        $arrayCoins = [$coin];
        $this->coinDataSource
            ->expects('getCoinInfo')
            ->with('90')
            ->andReturn($coin);
        $this->coinDataSource
            ->expects('getCoinsWallet')
            ->with()
            ->andReturn($arrayCoins);

        $response = $this->post(
            '/api/coin/sell',
            ['coin_id' => '90', 'wallet_id' => 'wallet_id', 'amountUSD' => '1.2']
        );

        $response->assertOk();
        $response->assertExactJson(['coin_id' => '90']);
    }
}

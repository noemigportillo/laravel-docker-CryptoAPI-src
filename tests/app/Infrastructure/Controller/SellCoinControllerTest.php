<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private ApiCoinRepository $apiCoinRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->apiCoinRepository = Mockery::mock(ApiCoinRepository::class);
        $this->app->bind(walletDataSource::class, function () {
            return $this->walletDataSource;
        });
        $this->app->bind(ApiCoinRepository::class, function () {
            return $this->apiCoinRepository;
        });
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $this->apiCoinRepository
            ->shouldReceive('buySell')
            ->with('coin_id', 0)
            ->andReturnNull();

        $response = $this->post('/api/coin/sell', ['coin_id' => 'coin_id',
            'wallet_id' => 'wallet_id', 'amount_usd' => '1.2']);

        $response->assertNotFound();
        $response->assertExactJson(['a coin with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function successfulOperation()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 5, 27086.37);
        $coin2 = new Coin('80', "Ethereum", "ETH", 5, 1848.35);
        $coins = array($coin, $coin2);
        $wallet = new Wallet("userId", "wallet_id", $coins, 0.4);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);

        $this->walletDataSource
            ->shouldReceive('saveWallet')
            ->once()
            ->with($wallet);

        $this->apiCoinRepository
            ->shouldReceive('buySell')
            ->with('90', 0)
            ->andReturn($coin);

        $response = $this->post('/api/coin/sell', ['coin_id' => '90',
            'wallet_id' => 'wallet_id', 'amount_usd' => '0']);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(['successful operation']);
    }
}

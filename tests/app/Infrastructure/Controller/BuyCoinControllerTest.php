<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;
use App\Domain\Coin;
use App\Domain\Wallet;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class BuyCoinControllerTest extends TestCase
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
            ->shouldReceive('CalculateAmountOfCoinWithAmountUsd')
            ->with('coin_id', 0)
            ->andReturnNull();

        $response = $this->post('/api/coin/buy', ['coin_id' => 'coin_id',
            'wallet_id' => 'wallet_id', 'amount_usd' => '1.2']);

        $response->assertNotFound();
        $response->assertExactJson(['a coin with the specified ID was not found.']);
    }
    /**
     * @test
     */
    public function buyCoinSuccess()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 2, 40.000);
        $coins = array($coin);
        $wallet = new Wallet("user_Id", "wallet_id", $coins, 0);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);

        $this->walletDataSource
            ->shouldReceive('saveWallet')
            ->once()
            ->with($wallet);

        $this->apiCoinRepository
            ->shouldReceive('CalculateAmountOfCoinWithAmountUsd')
            ->with('90', 100)
            ->andReturn($coin);

        $response = $this->post('/api/coin/buy', ['coin_id' => '90',
            'wallet_id' => 'wallet_id', 'amount_usd' => '100']);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(['successful operation']);
    }
}

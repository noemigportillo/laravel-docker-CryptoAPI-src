<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;
use App\Application\BuyCoinService;
use Mockery;
use Tests\TestCase;

class BuyCoinControllerTest1 extends TestCase
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

        $response = $this->post('/api/coin/buy', ['coin_id' => 'coin_id',
            'wallet_id' => 'wallet_id', 'amountUSD' => '1.2']);

        $response->assertNotFound();
        $response->assertExactJson(['a coin with the specified ID was not found.']);
    }
}

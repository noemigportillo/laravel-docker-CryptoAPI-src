<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;
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
}

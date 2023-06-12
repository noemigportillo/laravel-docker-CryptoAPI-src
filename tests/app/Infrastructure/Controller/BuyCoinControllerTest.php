<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\BuyCoin\BuyCoinService;
use App\Application\CoinDataSource\CoinDataSource;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class BuyCoinControllerTest extends TestCase
{
    private BuyCoinService $buyCoinService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->buyCoinService = Mockery::mock(BuyCoinService::class);
        $this->app->bind(BuyCoinService::class, function () {
            return $this->buyCoinService;
        });
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->buyCoinService
            ->expects('execute')
            ->andThrow(WalletNotFoundException::class);

        $response = $this->post('/api/coin/buy', ['coin_id' => 'coin_id',
            'wallet_id' => 'wallet_id', 'amount_usd' => '1.2']);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['A wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $this->buyCoinService
            ->expects('execute')
            ->andThrow(CoinNotFoundException::class);

        $response = $this->post('/api/coin/buy', ['coin_id' => 'coin_id',
            'wallet_id' => 'wallet_id', 'amount_usd' => '1.2']);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['A coin with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function buyCoinSuccess()
    {
        $this->buyCoinService
            ->shouldReceive('execute')
            ->once()
            ->with(90, 'wallet_id', 100);

        $response = $this->post('/api/coin/buy', ['coin_id' => '90',
            'wallet_id' => 'wallet_id', 'amount_usd' => '100']);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(['successful operation']);
    }
}

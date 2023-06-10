<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\InsuficientAmountException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\SellCoin\SellCoinService;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    private SellCoinService $sellCoinService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sellCoinService = Mockery::mock(SellCoinService::class);
        $this->app->bind(SellCoinService::class, function () {
            return $this->sellCoinService;
        });
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->sellCoinService
            ->expects('execute')
            ->andThrow(WalletNotFoundException::class);

        $response = $this->post('/api/coin/sell', ['coin_id' => 'coin_id',
            'wallet_id' => 'wallet_id', 'amount_usd' => '1.2']);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['A wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $this->sellCoinService
            ->expects('execute')
            ->andThrow(CoinNotFoundException::class);

        $response = $this->post('/api/coin/sell', ['coin_id' => 'coin_id',
            'wallet_id' => 'wallet_id', 'amount_usd' => '1.2']);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['A coin with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function notEnoughAmountToSell()
    {
        $this->sellCoinService
            ->expects('execute')
            ->andThrow(InsuficientAmountException::class);

        $response = $this->post('/api/coin/sell', ['coin_id' => 'coin_id',
            'wallet_id' => 'wallet_id', 'amount_usd' => '1.2']);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertExactJson(['Not enough amount.']);
    }

    /**
     * @test
     */
    public function successfulOperation()
    {
        $this->sellCoinService
            ->shouldReceive('execute')
            ->once()
            ->with(90, 'wallet_id', 100);

        $response = $this->post('/api/coin/sell', ['coin_id' => 90,
            'wallet_id' => 'wallet_id', 'amount_usd' => 100]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson(['successful operation']);
    }
}

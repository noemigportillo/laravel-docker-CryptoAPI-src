<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\UserDataSource\UserDataSource;
use App\Application\WalletCryptocurrenciesService;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class GetWalletCryptocurrenciesControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    /*private WalletCryptocurrenciesService $walletCryptocurrenciesService;*/
    protected function setUp(): void
    {
        parent::setUp();
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->app->bind(walletDataSource::class, function () {
            return $this->walletDataSource;
        });
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with('walletId')
            ->andReturnNull();

        $response = $this->get('/api/wallet/walletId');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['a wallet with the specified ID was not found.']);
    }

    /**
    * @test
    */
    public function getWalletCryptocurrenciesOkay()
    {
        $coin = new Coin("coinId", "name", "symbol", 0.2, 0.3);
        $coins = array($coin);
        $wallet = new Wallet("userId", "walletId", $coins, 0.4);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with('walletId')
            ->andReturn($wallet);

        $response = $this->get('/api/wallet/walletId');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([[
            "coin_id" => "coinId",
            "name" => "name",
            "symbol" => "symbol",
            "amount" => 0.2,
            "value_usd" => 0.3
        ]]);
    }
}

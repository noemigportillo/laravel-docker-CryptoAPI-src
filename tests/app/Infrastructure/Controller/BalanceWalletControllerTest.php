<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\BalanceWallet\BalanceWalletService;
use App\Application\BuyCoinService;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletCryptocurrenciesService;
use App\Application\WalletDataSource\WalletDataSource;
use App\Infrastructure\Persistence\APIClient;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class BalanceWalletControllerTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private BalanceWalletService $balanceWalletService;
    private APIClient $apiClient;
    protected function setUp(): void
    {
        parent::setUp();
        $this->balanceWalletService = Mockery::mock(BalanceWalletService::class);
        $this->app->bind(BalanceWalletService::class, function () {
            return $this->balanceWalletService;
        });
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $this->balanceWalletService
            ->expects('execute')
            ->andThrow(WalletNotFoundException::class);

        $response = $this->get('/api/wallet/wallet_id/balance');

        $response->assertStatus(Response::HTTP_NOT_FOUND);
        $response->assertExactJson(['a wallet with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $this->balanceWalletService
            ->expects('execute')
            ->andThrow(CoinNotFoundException::class);

        $response = $this->get('/api/wallet/wallet_id/balance');

        $response->assertNotFound();
        $response->assertExactJson(['a coin with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function balanceReturnedSuccesfully()
    {
        $this->balanceWalletService
            ->expects('execute')
            ->with('wallet_id')
            ->andReturn(26721.88);

        $response = $this->get('/api/wallet/wallet_id/balance');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertExactJson([
            "balance_usd" => 26721.88
        ]);
    }
}

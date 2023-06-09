<?php

namespace Tests\app\Application;

use App\Application\BalanceWalletService;
use App\Application\BuyCoinService;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletCryptocurrenciesService;
use App\Infrastructure\Persistence\APIClient;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use PHPUnit\Framework\TestCase;
use Mockery;

class BalanceWalletServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private BalanceWalletService $balanceWalletService;
    private APIClient $apiClient;
    protected function setUp(): void
    {
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->apiClient = Mockery::mock(APIClient::class);
        $this->balanceWalletService = new BalanceWalletService($this->walletDataSource, $this->apiClient);
    }

    /**
     * @test
     */
    public function walletNotFound()
    {
        $wallet_id = 'wallet_id';

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with($wallet_id)
            ->andReturnNull();

        $this->expectException(WalletNotFoundException::class);

        $this->balanceWalletService->execute($wallet_id);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $coin = new Coin('coin_id', "Bitcoin", "BTC", 1, 26721.88);
        $wallet = new Wallet("user_id", "wallet_id", [$coin], 26721.88);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);
        $this->apiClient
            ->expects('getCoinInfo')
            ->with("coin_id")
            ->andReturnNull();

        $this->expectException(CoinNotFoundException::class);

        $this->balanceWalletService->execute($wallet->getWalletId());
    }

    /**
     * @test
     */
    public function balanceReturnedSuccesfully()
    {
        $apiClient = new APIClient();
        $coin1 = $apiClient->getCoinInfo(90);
        $coin1->setAmount(4);
        $coin2 = $apiClient->getCoinInfo(80);
        $coin2->setAmount(4);
        $wallet = new Wallet("user_id", "wallet_id", [$coin1, $coin2], 26721.88);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);
        $this->apiClient
            ->expects('getCoinInfo')
            ->with(90)
            ->andReturn($coin1);
        $this->apiClient
            ->expects('getCoinInfo')
            ->with(80)
            ->andReturn($coin2);

        $balance = $this->balanceWalletService->execute($wallet->getWalletId());

        $this->assertEquals(
            $balance,
            ($coin1->getAmount() * $coin1->getValueUsd()) + ($coin2->getAmount() * $coin2->getValueUsd())
        );
    }
}

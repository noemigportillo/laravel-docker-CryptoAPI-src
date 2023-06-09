<?php

namespace Tests\app\Application;

use App\Application\BalanceWalletService;
use App\Application\BuyCoinService;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletCryptocurrenciesService;
use App\Infrastructure\Persistence\FileWalletDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use PHPUnit\Framework\TestCase;
use Mockery;

class BalanceWalletServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private BalanceWalletService $balanceWalletService;
    protected function setUp(): void
    {
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->balanceWalletService = new BalanceWalletService($this->walletDataSource);
    }
    /**
     * @test
     */
    public function walletNotFound()
    {
        $wallet_id = 'walletId';

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with($wallet_id)
            ->andReturnNull();

        $balanceWalletService = new BalanceWalletService($this->walletDataSource);
        $this->expectException(WalletNotFoundException::class);
        $balanceWalletService->execute($wallet_id);
    }
    /**
     * @test
     */
    public function walletWithNotCoinReturnsBalance()
    {
        $wallet = new Wallet("userId", "walletId", [], 1000.45);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("walletId")
            ->andReturn($wallet);

        $balance = $this->balanceWalletService->execute("walletId");
        $this->assertEquals(1000.45, $balance);
    }
}

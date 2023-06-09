<?php

namespace Tests\app\Application;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\SellCoin\SellCoinService;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\FileWalletDataSource;
use Exception;
use Mockery;
use PHPUnit\Framework\TestCase;

class SellCoinServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private FileWalletDataSource $fileWalletDataSource;

    protected function setUp(): void
    {
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->fileWalletDataSource = new FileWalletDataSource();
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

        $sellCoinService = new SellCoinService($this->walletDataSource);
        $this->expectException(WalletNotFoundException::class);
        $sellCoinService->execute("90", $wallet_id, 3);
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 0, 26721.88);
        $coins = array($coin);
        $wallet = new Wallet("userId", "wallet_id", $coins, 0.4);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);

        $sellCoinService = new SellCoinService($this->walletDataSource);
        $this->expectException(CoinNotFoundException::class);
        $sellCoinService->execute("coin", "wallet_id", 3);
    }

    /**
     * @test
     */
    public function coinFoundInWalletAndNotEnoughAmount()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 0, 26721.88);
        $coins = array($coin);
        $wallet = new Wallet("userId", "wallet_id", $coins, 0.4);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);

        $sellCoinService = new SellCoinService($this->walletDataSource);
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('No suficiente amount');
        $sellCoinService->execute("90", "wallet_id", 30);
    }
    /**
     * @test
     */
    public function coinFoundInWalletAndEnoughAmount()
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

        $sellCoinService = new SellCoinService($this->walletDataSource);
        $sellCoinService->execute("90", "wallet_id", 30000);
        $this->assertTrue(true);
    }
}

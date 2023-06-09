<?php

namespace Tests\app\Application;

use App\Application\BuyCoin\BuyCoinService;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\FileWalletDataSource;
use Mockery;
use PHPUnit\Framework\TestCase;

class BuyCoinServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private FileWalletDataSource $fileWalletDataSource;
    protected function setUp(): void
    {
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->fileWalletDataSource = new FileWalletDataSource($this->walletDataSource);
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

        $buyCoinService = new BuyCoinService($this->walletDataSource);
        $this->expectException(WalletNotFoundException::class);
        $buyCoinService->execute("90", $wallet_id, "3");
    }

    /**
     * @test
     */
    public function coinNotFound()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 0, 26721.88);
        $coins = array($coin);
        $wallet = new Wallet("userId", "walletId", $coins, 0.4);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("walletId")
            ->andReturn($wallet);
        $buyCoinService = new BuyCoinService($this->walletDataSource);
        $this->expectException(CoinNotFoundException::class);
        $buyCoinService->execute("coin", "walletId", "3");
    }
    /**
     * @test
     */
    public function coinNotFoundInWallet()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 30, 26721.88);
        $coins = array($coin);
        $wallet = new Wallet("userId", "walletId", $coins, 0.4);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("walletId")
            ->andReturn($wallet);
        $this->walletDataSource
            ->shouldReceive('saveWallet')
            ->once()
            ->with($wallet);
        $buyCoinService = new BuyCoinService($this->walletDataSource);
        $buyCoinService->execute("29", "walletId", 30000);
        $coins = $wallet->getCoins();
        $coin = $buyCoinService->findCoinById($coins, "29");
        $this->assertEquals("CloakCoin", $coin->getName());
    }

    /**
     * @test
     */
    public function coinFoundInWallet()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 30, 26721.88);
        $coins = array($coin);
        $wallet = new Wallet("userId", "walletId", $coins, 0.4);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("walletId")
            ->andReturn($wallet);
        $this->walletDataSource
            ->shouldReceive('saveWallet')
            ->once()
            ->with($wallet);
        $buyCoinService = new BuyCoinService($this->walletDataSource);
        $buyCoinService->execute("90", "walletId", 30000);
        $coins = $wallet->getCoins();
        $coin = $buyCoinService->findCoinById($coins, "90");
        $this->assertNotEquals(30, $coin->getAmount());
    }
}

<?php

namespace Tests\app\Application;

use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\InsuficientAmountException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\SellCoin\SellCoinService;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\FileWalletDataSource;
use Mockery;
use PHPUnit\Framework\TestCase;

class SellCoinServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private FileWalletDataSource $fileWalletDataSource;
    private SellCoinService $sellCoinService;

    protected function setUp(): void
    {
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->fileWalletDataSource = new FileWalletDataSource();
        $this->sellCoinService = new SellCoinService($this->walletDataSource);
    }

    /**
     * @test
     */
    public function walletNotFoundThrowsException()
    {
        $wallet_id = 'walletId';
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with($wallet_id)
            ->andReturnNull();

        $this->expectException(WalletNotFoundException::class);

        $this->sellCoinService->execute("90", $wallet_id, 3);
    }

    /**
     * @test
     */
    public function coinNotFoundInWalletThrowsException()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 1, 27086.37);
        $wallet = new Wallet("userId", "wallet_id", [$coin], 27086.37);
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);

        $this->expectException(CoinNotFoundException::class);

        $this->sellCoinService->execute("coin", "wallet_id", 3);
    }

    /**
     * @test
     */
    public function notEnoughAmountToSell()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 1, 27086.37);
        $wallet = new Wallet("userId", "wallet_id", [$coin], 27086.37);
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);

        $this->expectException(InsuficientAmountException::class);

        $this->sellCoinService->execute("90", "wallet_id", 300000);
    }

    /**
     * @test
     */
    public function sellOperationCompletesSuccessfully()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 5, 27086.37);
        $wallet = new Wallet("user_id", "wallet_id", [$coin], (27086.37 * 5));
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);
        $this->walletDataSource
            ->shouldReceive('saveWallet')
            ->once()
            ->with($wallet);

        $this->sellCoinService->execute("90", "wallet_id", 10000);

        $coins = $wallet->getCoins();
        $coin = $this->sellCoinService->findCoinById($coins, "90");
        $this->assertLessThan(5, $coin->getAmount());
    }
}

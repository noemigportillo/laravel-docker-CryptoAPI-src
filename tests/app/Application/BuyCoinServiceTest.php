<?php

namespace Tests\app\Application;

use App\Application\BuyCoin\BuyCoinService;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;
use App\Infrastructure\Persistence\FileWalletDataSource;
use Mockery;
use PHPUnit\Framework\TestCase;

class BuyCoinServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private FileWalletDataSource $fileWalletDataSource;
    private BuyCoinService $buyCoinService;
    private ApiCoinRepository $apiCoinRepository;

    protected function setUp(): void
    {
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->fileWalletDataSource = new FileWalletDataSource();
        $this->apiCoinRepository = Mockery::mock(ApiCoinRepository::class);
        $this->buyCoinService = new BuyCoinService($this->walletDataSource, $this->apiCoinRepository);
    }

    /**
     * @test
     */
    public function walletNotFoundThrowsException()
    {
        $wallet_id = 'wallet_id';
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with($wallet_id)
            ->andReturnNull();

        $this->expectException(WalletNotFoundException::class);

        $this->buyCoinService->execute("90", $wallet_id, "3");
    }

    /**
     * @test
     */
    public function coinNotFoundInApiThrowsException()
    {
        $coin = new Coin('90', "Bitcoin", "BTC", 1, 26721.88);
        $wallet = new Wallet("userId", "wallet_id", [$coin], 26721.88);
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);
        $this->apiCoinRepository
            ->expects('calculateAmountOfCoinWithAmountUsd')
            ->with("90", 10000)
            ->andReturnNull();

        $this->expectException(CoinNotFoundException::class);

        $this->buyCoinService->execute("90", "wallet_id", 10000);
    }

    /**
     * @test
     */
    public function coinNotYetInWallet()
    {
        $apiCoinRepository = new ApiCoinRepository();
        $coin_api = $apiCoinRepository->calculateAmountOfCoinWithAmountUsd("90", 3);
        $wallet = new Wallet("user_id", "wallet_id", [], 0);
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("wallet_id")
            ->andReturn($wallet);
        $this->walletDataSource
            ->shouldReceive('saveWallet')
            ->once()
            ->with($wallet);
        $this->apiCoinRepository
            ->expects('calculateAmountOfCoinWithAmountUsd')
            ->with("90", 3)
            ->andReturn($coin_api);

        $this->buyCoinService->execute("90", "wallet_id", 3);

        $coins = $wallet->getCoins();
        $coin = $this->buyCoinService->findCoinById($coins, "90");
        $this->assertEquals("Bitcoin", $coin->getName());
    }

    /**
     * @test
     */
    public function coinAlreadyInWallet()
    {
        $apiCoinRepository = new ApiCoinRepository();
        $coin_api = $apiCoinRepository->calculateAmountOfCoinWithAmountUsd("90", 300);
        $coin = new Coin('90', "Bitcoin", "BTC", 1, 26721.88);
        $wallet = new Wallet("userId", "walletId", [$coin], 26721.88);
        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("walletId")
            ->andReturn($wallet);
        $this->walletDataSource
            ->shouldReceive('saveWallet')
            ->once()
            ->with($wallet);
        $this->apiCoinRepository
            ->expects('calculateAmountOfCoinWithAmountUsd')
            ->with("90", 300)
            ->andReturn($coin_api);

        $this->buyCoinService->execute("90", "walletId", 300);

        $coins = $wallet->getCoins();
        $coin = $this->buyCoinService->findCoinById($coins, "90");
        $this->assertGreaterThan(1, $coin->getAmount());
    }
}

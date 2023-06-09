<?php

namespace Tests\app\Application;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletCryptocurrencies\WalletCryptocurrenciesService;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Mockery;
use Tests\TestCase;

class WalletCryptocurrenciesServiceTest extends TestCase
{
    private WalletDataSource $walletDataSource;
    private WalletCryptocurrenciesService $walletCryptocurrenciesService;

    protected function setUp(): void
    {
        $this->walletDataSource = Mockery::mock(WalletDataSource::class);
        $this->walletCryptocurrenciesService = new WalletCryptocurrenciesService($this->walletDataSource);
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

        $this->expectException(WalletNotFoundException::class);

        $this->walletCryptocurrenciesService->execute($wallet_id);
    }

    /**
     * @test
     */
    public function walletFound()
    {
        $coin = new Coin("coinId", "name", "symbol", 0.2, 0.3);
        $coins = array($coin);
        $wallet = new Wallet("userId", "walletId", $coins, 0.4);

        $this->walletDataSource
            ->expects('getWalletInfo')
            ->with("walletId")
            ->andReturn($wallet);

        $result_wallet = $this->walletCryptocurrenciesService->execute("walletId");

        $this->assertEquals($coins, $result_wallet);
    }
}

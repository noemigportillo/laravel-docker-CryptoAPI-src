<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\Exceptions\WalletNotFoundException;
use App\Application\UserDataSource\UserDataSource;
use App\Application\SellCoinService;
use App\Application\CoinDataSource\CoinDataSource;
use App\Domain\Coin;
use App\Domain\Wallet;
use Exception;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class SellCoinControllerTest extends TestCase
{
    private CoinDataSource $coinDataSource;

    protected function setUp(): void
    {
        parent::setUp();
        $this->coinDataSource = Mockery::mock(CoinDataSource::class);
        $this->app->bind(coinDataSource::class, function () {
            return $this->coinDataSource;
        });
    }
}

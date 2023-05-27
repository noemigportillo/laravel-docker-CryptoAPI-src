<?php

namespace Tests\app\Application;

use App\Application\OpenWallet\OpenWalletService;
use App\Application\UserDataSource\UserDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\User;
use Mockery;
use Tests\TestCase;

class OpenWalletServiceTest extends TestCase
{
    private UserDataSource $userDataSource;
    private WalletDataSource $walletDataSource;
    private OpenWalletService $openWalletService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userDataSource = Mockery::mock(UserDataSource::class);
        $this->app->bind(UserDataSource::class, function () {
            return $this->userDataSource;
        });

        $this->openWalletService = new OpenWalletService($this->userDataSource);
    }

    /**
     * @test
     */
    public function isWalletOpening()
    {
        $this->userDataSource->expects("findById")->andReturn(new User("user_id", "email@email.com"));

        $result = $this->openWalletService->execute("");

        $this->assertEquals("user_id", $result);
    }
}

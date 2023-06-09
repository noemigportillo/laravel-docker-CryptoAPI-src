<?php

namespace Tests\app\Application;

use App\Application\OpenWallet\OpenWalletService;
use App\Application\UserDataSource\UserDataSource;
use App\Infrastructure\Persistence\FileUserDataSource;
use Tests\TestCase;

class OpenWalletServiceTest extends TestCase
{
    private UserDataSource $userDataSource;
    private OpenWalletService $openWalletService;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userDataSource = new FileUserDataSource();
        $this->openWalletService = new OpenWalletService();
    }

    /**
     * @test
     */
    public function userNotFoundThrowsException()
    {
        $this->expectExceptionMessage("User not found");

        $this->openWalletService->execute("user_id");
    }

    /**
     * @test
     */
    public function walletOpenSuccess()
    {
        $this->userDataSource->addUser("user_id", "email@email.com");

        $result = $this->openWalletService->execute("user_id");

        $this->assertEquals("wallet_user_id", $result);
    }
}

<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;
use App\Infrastructure\Persistence\FileUserDataSource;
use Mockery;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    private UserDataSource $userDataSource;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userDataSource = new FileUserDataSource();
    }
    /**
     * @test
     */
    public function userNotFoundCauseErrorOpeningWallet()
    {
        $response = $this->post('/api/wallet/open', ['user_id' => 'user_id']);

        $response->assertNotFound();
        $response->assertExactJson([
            'A user with the specified ID was not found.']);
    }

    /**
     * @test
     */
    public function openedWalletReturnsOk()
    {
        $this->userDataSource->addUser("user_1", "email@email.com");

        $response = $this->post('/api/wallet/open', ['user_id' => 'user_1']);

        $response->assertOk();
        $response->assertExactJson(['wallet_id' => 'wallet_user_1']);
    }
}

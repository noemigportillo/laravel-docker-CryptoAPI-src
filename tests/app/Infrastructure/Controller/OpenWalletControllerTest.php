<?php

namespace Tests\app\Infrastructure\Controller;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;
use Mockery;
use Tests\TestCase;

class OpenWalletControllerTest extends TestCase
{
    private UserDataSource $userDataSource;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userDataSource = Mockery::mock(UserDataSource::class);
        $this->app->bind(UserDataSource::class, function () {
            return $this->userDataSource;
        });
    }
    /**
     * @test
     */
    public function userNotFoundCauseErrorOpeningWallet()
    {
        $this->userDataSource
            ->expects('findById')
            ->with('user_id')
            ->andReturn(null);

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
        $this->userDataSource
            ->expects('findById')
            ->with('user_1')
            ->andReturn(new User("user_1", "email@email.com"));

        $response = $this->post('/api/wallet/open', ['user_id' => 'user_1']);

        $response->assertOk();
        $response->assertExactJson(['wallet_id' => 'user_1']);
    }
}

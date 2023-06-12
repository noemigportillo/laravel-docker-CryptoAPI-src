<?php

namespace App\Application\OpenWallet;

use App\Application\Exceptions\UserNotFoundException;
use App\Application\UserDataSource\UserDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\FileUserDataSource;
use App\Infrastructure\Persistence\FileWalletDataSource;

class OpenWalletService
{
    private UserDataSource $userDataSource;
    private WalletDataSource $walletDataSource;

    public function __construct()
    {
        $this->userDataSource = new FileUserDataSource();
        $this->walletDataSource = new FileWalletDataSource();
    }

    /**
     * @throws UserNotFoundException
     */
    public function execute(string $user_id): ?string
    {
        $user = $this->userDataSource->findById($user_id);
        if (is_null($user)) {
            throw new UserNotFoundException();
        }

        $wallet = new Wallet($user->getId(), "wallet_" . $user->getId(), [], 0);
        $this->walletDataSource->saveWallet($wallet);
        return $wallet->getWalletId();
    }
}

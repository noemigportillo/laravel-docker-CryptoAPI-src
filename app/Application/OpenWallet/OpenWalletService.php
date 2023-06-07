<?php

namespace App\Application\OpenWallet;

use App\Application\UserDataSource\UserDataSource;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Wallet;
use App\Infrastructure\Persistence\FileUserDataSource;
use App\Infrastructure\Persistence\FileWalletDataSource;
use Exception;

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
     * @throws Exception
     */
    public function execute(string $user_id): ?string
    {
        $user = $this->userDataSource->findById($user_id);
        if (is_null($user)) {
            throw new Exception("User not found");
        }

        $wallet = new Wallet($user->getId(), "wallet_" . $user->getId(), [], 0);
        $this->walletDataSource->saveWallet($wallet);
        return $wallet->getWalletId();
    }
}

<?php

namespace App\Application\WalletDataSource;

use App\Domain\Wallet;

interface WalletDataSource
{
    public function getWalletInfo(string $wallet_id): ?Wallet;

    public function saveWallet(Wallet $wallet): void;
}

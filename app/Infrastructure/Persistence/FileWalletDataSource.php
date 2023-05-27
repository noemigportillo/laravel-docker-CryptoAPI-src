<?php

namespace App\Infrastructure\Persistence;

use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class FileWalletDataSource implements WalletDataSource
{
    public function saveWallet(Wallet $wallet): void
    {
        Cache::put($wallet->getWalletId(), $wallet);
    }
}

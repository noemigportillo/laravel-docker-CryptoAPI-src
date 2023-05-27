<?php

namespace App\Infrastructure\Persistence;

use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Wallet;
use Illuminate\Support\Facades\Cache;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 **/
class WalletCryptocurrenciesDataSource implements WalletDataSource
{
    public function getWalletInfo(string $wallet_id): ?Wallet
    {
        if (Cache::has($wallet_id)) {
            return Cache::get($wallet_id);
        }

        return null;
    }
}

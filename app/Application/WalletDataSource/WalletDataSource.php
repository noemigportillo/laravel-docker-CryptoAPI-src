<?php

namespace App\Application\WalletDataSource;

use App\Domain\Wallet;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
interface WalletDataSource
{
    public function saveWallet(Wallet $wallet): void;
}

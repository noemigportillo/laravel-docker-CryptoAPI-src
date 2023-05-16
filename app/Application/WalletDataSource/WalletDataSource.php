<?php

namespace App\Application\WalletDataSource;

use App\Domain\Wallet;

interface WalletDataSource
{
    public function getWalletInfo(string $wallet_id): ?Wallet;

    /*/**
     * @return Wallet[]
     */
    /*public function getAll(): array;*/
}

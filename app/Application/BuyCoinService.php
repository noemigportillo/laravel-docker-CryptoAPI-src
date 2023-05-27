<?php

namespace App\Application;

use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;
use mysql_xdevapi\Exception;

class BuyCoinService
{
    public function execute(string $coinId, string $walletId, float $amountUSD): void
    {
        $wallet = findById($walletId);
        if ($wallet == null) {
            throw new Exception('Wallet Not Found');
        }
    }
}

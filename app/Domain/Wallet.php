<?php

namespace App\Domain;

class Wallet
{
    private string $user_id;
    private string $wallet_id;
    private array $coins;
    private float $balance_usd;

    public function __construct(string $user_id, string $wallet_id, array $coins, float $balance_usd)
    {
        $this->user_id = $user_id;
        $this->wallet_id = $wallet_id;
        $this->coins = $coins;
        $this->balance_usd = $balance_usd;
    }

    public function getUserId(): string
    {
        return $this->user_id;
    }
    public function getWalletId(): string
    {
        return $this->wallet_id;
    }
    public function getCoins(): array
    {
        return $this->coins;
    }
    public function getBalanceUsd(): float
    {
        return $this->balance_usd;
    }
    public function setCoins($coins): void
    {
        $this->coins = $coins;
    }
}

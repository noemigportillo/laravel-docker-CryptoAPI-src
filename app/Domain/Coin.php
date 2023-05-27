<?php

namespace App\Domain;

class Coin
{
    private string $coin_id;
    private string $name;
    private string $symbol;
    private float $amount;
    private float $value_usd;


    public function __construct(string $coin_id, string $name, string $symbol, float $amount, float $value_usd)
    {
        $this->coin_id = $coin_id;
        $this->name = $name;
        $this->symbol = $symbol;
        $this->amount = $amount;
        $this->value_usd = $value_usd;
    }

    public function getId(): string
    {
        return $this->coin_id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function getSymbol(): string
    {
        return $this->symbol;
    }
    public function getAmount(): float
    {
        return $this->amount;
    }
    public function getValueUsd(): float
    {
        return $this->value_usd;
    }
    public function setId($coin_id): void
    {
        $this->coin_id = $coin_id;
    }
    public function setName($name): void
    {
        $this->name = $name;
    }
    public function setSymbol($symbol): void
    {
        $this->symbol = $symbol;
    }
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }
    public function setValueUsd($value_usd): void
    {
        $this->value_usd = $value_usd;
    }
}

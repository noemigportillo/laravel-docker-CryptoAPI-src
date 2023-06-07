<?php

namespace App\Application;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;

class BuyCoinService
{
    private ApiCoinRepository $apiCoinRepository;
    private WalletDataSource $walletDataSource;

    public function __construct(WalletDataSource $walletDataSource)
    {
        $this->walletDataSource = $walletDataSource;
    }
    public function findCoinById(array $coins, string $coinId): ?Coin
    {
        foreach ($coins as $coin) {
            if ($coin->getId() === $coinId) {
                return $coin;
            }
        }
        return null;
    }
    public function removeCoinById(array $coins, string $coinId): array
    {
        return array_filter($coins, function ($coin) use ($coinId) {
            return $coin->getId() !== $coinId;
        });
    }
    public function execute(string $coin_id, string $wallet_id, float $amount_usd): void
    {
        $wallet = $this->walletDataSource->getWalletInfo($wallet_id);
        if (is_null($wallet)) {
            throw new WalletNotFoundException();
        }
        $this->apiCoinRepository = new ApiCoinRepository();
        $coin = $this->apiCoinRepository->buySell($coin_id, $amount_usd);
        if (is_null($coin)) {
            throw new CoinNotFoundException();
        }
        $coinsWallet = $wallet->getCoins();
        $coinDeWallet = $this->findCoinById($coinsWallet, $coin_id);
        if (is_null($coinDeWallet)) {
            $coinsWallet = $wallet->getCoins();
            $coinsWallet[] = $coin;
            $wallet->setCoins($coinsWallet);
            $this->walletDataSource->saveWallet($wallet);
        } elseif (!is_null($coinDeWallet)) {
            $coin->setAmount($coinDeWallet->getAmount() + $coin->getAmount());
            $coinsWallet = $this->removeCoinById($coinsWallet, $coin_id);
            $coinsWallet[] = $coin;
            $wallet->setCoins($coinsWallet);
            $this->walletDataSource->saveWallet($wallet);
        }
    }
}

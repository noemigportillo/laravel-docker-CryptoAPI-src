<?php

namespace App\Application\SellCoin;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\InsuficientAmountException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;
use Mockery\Exception;

class SellCoinService
{
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
    public function unsetCoinById(array $coins, string $coinId): array
    {
        foreach ($coins as $i => $coin) {
            if ($coin->getId() === $coinId) {
                unset($coins[$i]);
            }
        }
        return $coins;
    }

    /**
     * @throws CoinNotFoundException
     * @throws WalletNotFoundException
     * @throws InsuficientAmountException
     */
    public function execute(string $coin_id, string $wallet_id, float $amount_usd): void
    {
        $wallet = $this->walletDataSource->getWalletInfo($wallet_id);
        if (is_null($wallet)) {
            throw new WalletNotFoundException();
        }
        $apiCoinRepository = new ApiCoinRepository();
        $coin = $apiCoinRepository->calculateAmountOfCoinWithAmountUsd($coin_id, $amount_usd);
        $coinsWallet = $wallet->getCoins();
        $coinOfWallet = $this->findCoinById($coinsWallet, $coin_id);
        if (is_null($coinOfWallet)) {
            throw new CoinNotFoundException();
        }
        if ($coinOfWallet->getAmount() < $coin->getAmount()) {
            throw new InsuficientAmountException();
        }
        $coin->setAmount($coinOfWallet->getAmount() - $coin->getAmount());
        $coinsWallet = $this->unsetCoinById($coinsWallet, $coin_id);
        $coinsWallet[] = $coin;
        $wallet->setCoins($coinsWallet);
        $this->walletDataSource->saveWallet($wallet);
    }
}

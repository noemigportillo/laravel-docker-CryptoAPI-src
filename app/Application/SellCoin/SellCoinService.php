<?php

namespace App\Application\SellCoin;

use App\Application\CoinDataSource\CoinDataSource;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use App\Application\WalletDataSource\WalletDataSource;
use App\Domain\Coin;
use App\Infrastructure\Persistence\ApiCoinDataSource\ApiCoinRepository;
use Mockery\Exception;

class SellCoinService
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
    public function unsetCoinById(array $coins, string $coinId): array
    {
        foreach ($coins as $i => $coin) {
            if ($coin->getId() === $coinId) {
                unset($coins[$i]);
            }
        }
        return $coins;
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
            throw new CoinNotFoundException();
        }
        if ($coinDeWallet->getAmount() < $coin->getAmount()) {
            throw new Exception("No suficiente amount");
        }
        $coin->setAmount($coinDeWallet->getAmount() - $coin->getAmount());
        $coinsWallet = $this->unsetCoinById($coinsWallet, $coin_id);
        $coinsWallet[] = $coin;
        $wallet->setCoins($coinsWallet);
        $this->walletDataSource->saveWallet($wallet);
    }
}

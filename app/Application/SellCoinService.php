<?php

namespace App\Application;

use App\Application\CoinDataSource\CoinDataSource;
use App\Infrastructure\Exceptions\CoinNotFoundException;
use App\Domain\Coin;
use Mockery\Exception;

class SellCoinService
{
    private CoinDataSource $coinDataSource;
    //private WalletDataSource $walletDataSource;

    /**
     * @param CoinDataSource $coinDataSource
     */
    public function __construct(CoinDataSource $coinDataSource)
    {
        $this->coinDataSource = $coinDataSource;
        //$this->walletDataSource = new FileWalletDataSource();
    }
    public function findCoinById(array $coins, string $coinId): ?Coin
    {
        foreach ($coins as $coin) {
            if ($coin->getId() === $coinId) {
                return $coin;
            }
        }
        return null; //Si no se encuentra la moneda, devuelve null
    }
    public function execute(string $coin_id, $wallet_id, $amountUSD): ?Coin
    {
        $coin = $this->coinDataSource->getCoinInfo($coin_id);
        if (is_null($coin)) {
            throw new CoinNotFoundException();
        }
        /*$wallet = $this->walletDataSource->getWalletInfo($wallet_id);
        if(is_null($wallet){
            throw new WalletNotFoundException();
        }*/
        //comprobar que se puede vender
        //$coins = $wallet->getCoins();
        $coins = $this->coinDataSource->getCoinsWallet();
        $coin2 = $this->findCoinById($coins, $coin_id);
        if (is_null($coin2)) { //si no esta en mi wallet
            throw new CoinNotFoundException();
        }
        return $coin;
    }
}

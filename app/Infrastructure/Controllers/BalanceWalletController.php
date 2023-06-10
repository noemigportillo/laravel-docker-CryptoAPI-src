<?php

namespace App\Infrastructure\Controllers;

use App\Application\BalanceWallet\BalanceWalletService;
use App\Application\Exceptions\CoinNotFoundException;
use App\Application\Exceptions\WalletNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class BalanceWalletController extends BaseController
{
    private BalanceWalletService $balanceWalletService;

    /**
     * @param BalanceWalletService $balanceWalletService
     */
    public function __construct(BalanceWalletService $balanceWalletService)
    {
        $this->balanceWalletService = $balanceWalletService;
    }

    public function __invoke(string $wallet_id): JsonResponse
    {
        try {
            $balance = $this->balanceWalletService->execute($wallet_id);

            return response()->json([
                'balance_usd' => $balance
            ], Response::HTTP_OK);
        } catch (WalletNotFoundException $e) {
            return response()->json([
                'a wallet with the specified ID was not found.',
            ], Response::HTTP_NOT_FOUND);
        } catch (CoinNotFoundException $e) {
            return response()->json([
                'a coin with the specified ID was not found.'
            ], Response::HTTP_NOT_FOUND);
        }
    }
}

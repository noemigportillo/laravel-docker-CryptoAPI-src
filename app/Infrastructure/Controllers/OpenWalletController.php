<?php

namespace App\Infrastructure\Controllers;

use App\Application\OpenWallet\OpenWalletService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class OpenWalletController extends BaseController
{
    private OpenWalletService $openWalletService;

    public function __construct(OpenWalletService $openWalletService)
    {
        $this->openWalletService = $openWalletService;
    }

    public function __invoke(Request $bodyPetition): JsonResponse
    {
        try {
            $wallet = $this->openWalletService->execute($bodyPetition->input("user_id"));
        } catch (\Exception $ex) {
            return response()->json([
                'A user with the specified ID was not found.'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'wallet_id' => $wallet
        ], Response::HTTP_OK);
    }
}

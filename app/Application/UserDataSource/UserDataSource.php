<?php

namespace App\Application\UserDataSource;

use App\Domain\Wallet;

interface UserDataSource
{
    public function findByEmail(string $email): Wallet;

    /**
     * @return Wallet[]
     */
    public function getAll(): array;
}

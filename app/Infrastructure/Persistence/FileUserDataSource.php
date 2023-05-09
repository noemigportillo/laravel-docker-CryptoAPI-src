<?php

namespace App\Infrastructure\Persistence;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\Wallet;

class FileUserDataSource implements UserDataSource
{
    public function findByEmail(string $email): Wallet
    {
        return new Wallet(1, "email@email.com");
    }

    public function getAll(): array
    {
        return [new Wallet(1, "email@email.com"), new Wallet(2, "another_email@email.com")];
    }
}

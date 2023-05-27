<?php

namespace App\Application\UserDataSource;

use App\Domain\User;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
interface UserDataSource
{
    public function findByEmail(string $user_id): Wallet;
}

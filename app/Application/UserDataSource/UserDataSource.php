<?php

namespace App\Application\UserDataSource;

use App\Domain\User;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
interface UserDataSource
{
    public function findById(string $user_id): ?User;
}

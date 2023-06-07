<?php

namespace App\Infrastructure\Persistence;

use App\Application\UserDataSource\UserDataSource;
use App\Domain\User;
use Illuminate\Support\Facades\Cache;

/**
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class FileUserDataSource implements UserDataSource
{
    public function findById(string $user_id): ?User
    {
        if (!Cache::has($user_id)) {
            return null;
        }
        return Cache::get($user_id);
    }

    public function addUser(string $user_id, string $email): User
    {
        $user = new User($user_id, $email);
        Cache::put($user_id, $user);
        return $user;
    }
}

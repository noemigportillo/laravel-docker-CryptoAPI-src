<?php

namespace App\Application\UserDataSource;

use App\Domain\User;

interface UserDataSource
{
    public function findById(string $user_id): ?User;

    public function addUser(string $user_id, string $email): User;
}

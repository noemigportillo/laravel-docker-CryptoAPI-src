<?php

namespace App\Domain;

class User
{
    private string $user_id;
    private string $email;

    public function __construct(string $user_id, string $email)
    {
        $this->user_id = $user_id;
        $this->email = $email;
    }

    public function getId(): string
    {
        return $this->user_id;
    }
}

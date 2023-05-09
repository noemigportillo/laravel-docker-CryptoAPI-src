<?php

namespace App\Domain;

class User
{
    private int $idd;
    private string $email;

    public function __construct(int $idd, string $email)
    {
        $this->idd = $idd;
        $this->email = $email;
    }

    public function getId(): int
    {
        return $this->idd;
    }
}

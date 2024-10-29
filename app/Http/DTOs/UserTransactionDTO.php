<?php

namespace App\DTOs;

class UserTransactionDTO
{
    public function __construct(
        public readonly float $amount,
        public readonly string $currency,
        public readonly string $email,
        public readonly string $status,
        public readonly string $date,
        public readonly string $identifier,
        public readonly string $provider
    ) {}
}
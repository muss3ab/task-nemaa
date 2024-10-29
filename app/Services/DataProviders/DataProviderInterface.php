<?php

namespace App\Services\DataProviders;

use App\DTOs\UserTransactionDTO;

interface DataProviderInterface
{
    public function getName(): string;
    public function getTransactions(): \Generator;
    public function applyFilters(array $filters): self;
}

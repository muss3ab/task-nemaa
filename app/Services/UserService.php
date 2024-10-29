<?php

namespace App\Services;

use App\Services\DataProviders\DataProviderFactory;
use App\DTOs\UserTransactionDTO;

class UserService
{
    public function __construct(
        private readonly DataProviderFactory $providerFactory
    ) {}

    public function getFilteredTransactions(array $filters): array
    {
        $results = [];
        $providers = $this->getProvidersToProcess($filters);

        foreach ($providers as $provider) {
            $providerFilters = $this->transformFilters($filters);
            $transactions = $provider->applyFilters($providerFilters)->getTransactions();

            foreach ($transactions as $transaction) {
                $results[] = $this->formatTransaction($transaction);
            }
        }

        return $results;
    }

    private function getProvidersToProcess(array $filters): array
    {
        if (isset($filters['provider'])) {
            return [$this->providerFactory->getProvider($filters['provider'])];
        }

        return $this->providerFactory->getAllProviders();
    }

    private function transformFilters(array $filters): array
    {
        $transformed = [];

        if (isset($filters['statusCode'])) {
            $transformed['status'] = $filters['statusCode'];
        }
        if (isset($filters['balanceMin'])) {
            $transformed['balanceMin'] = (float) $filters['balanceMin'];
        }
        if (isset($filters['balanceMax'])) {
            $transformed['balanceMax'] = (float) $filters['balanceMax'];
        }
        if (isset($filters['currency'])) {
            $transformed['currency'] = $filters['currency'];
        }

        return $transformed;
    }

    private function formatTransaction(UserTransactionDTO $transaction): array
    {
        return [
            'id' => $transaction->identifier,
            'email' => $transaction->email,
            'amount' => $transaction->amount,
            'currency' => $transaction->currency,
            'status' => $transaction->status,
            'date' => $transaction->date,
            'provider' => $transaction->provider
        ];
    }
}

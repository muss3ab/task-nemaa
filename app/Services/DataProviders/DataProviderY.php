<?php

namespace App\Services\DataProviders;

use App\DTOs\UserTransactionDTO;
use App\Enums\TransactionStatus;

class DataProviderY implements DataProviderInterface
{
    private array $filters = [];
    private string $jsonPath;

    public function __construct()
    {
        $this->jsonPath = storage_path('app/DataProviderY.json');
    }

    public function getName(): string
    {
        return 'DataProviderY';
    }

    public function getTransactions(): \Generator
    {
        $handle = fopen($this->jsonPath, 'r');
        
        while (($line = fgets($handle)) !== false) {
            $transaction = json_decode($line, true);
            
            if ($this->passesFilters($transaction)) {
                yield $this->transformToDTO($transaction);
            }
        }
        
        fclose($handle);
    }

    private function transformToDTO(array $data): UserTransactionDTO
    {
        return new UserTransactionDTO(
            amount: $data['balance'],
            currency: $data['currency'],
            email: $data['email'],
            status: TransactionStatus::fromProviderYCode($data['status'])->value,
            date: $this->formatDate($data['created_at']),
            identifier: $data['id'],
            provider: $this->getName()
        );
    }

    private function formatDate(string $date): string
    {
        // Convert from DD/MM/YYYY to YYYY-MM-DD
        $dateObj = \DateTime::createFromFormat('d/m/Y', $date);
        return $dateObj->format('Y-m-d');
    }

    public function applyFilters(array $filters): self
    {
        $this->filters = $filters;
        return $this;
    }

    private function passesFilters(array $transaction): bool
    {
        foreach ($this->filters as $key => $value) {
            switch ($key) {
                case 'status':
                    if (TransactionStatus::fromProviderYCode($transaction['status'])->value !== $value) {
                        return false;
                    }
                    break;
                case 'currency':
                    if ($transaction['currency'] !== $value) {
                        return false;
                    }
                    break;
                case 'balanceMin':
                    if ($transaction['balance'] < $value) {
                        return false;
                    }
                    break;
                case 'balanceMax':
                    if ($transaction['balance'] > $value) {
                        return false;
                    }
                    break;
            }
        }
        return true;
    }
}

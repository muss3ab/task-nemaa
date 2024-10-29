<?php

namespace App\Services\DataProviders;

use App\DTOs\UserTransactionDTO;
use App\Enums\TransactionStatus;

class DataProviderX implements DataProviderInterface
{
    private array $filters = [];
    private string $jsonPath;

    public function __construct()
    {
        $this->jsonPath = storage_path('app/DataProviderX.json');
    }

    public function getName(): string
    {
        return 'DataProviderX';
    }

    public function getTransactions(): \Generator
    {
        if (!file_exists($this->jsonPath)) {
            throw new \RuntimeException("Data file not found: {$this->jsonPath}");
        }

        $handle = fopen($this->jsonPath, 'r');
        
        if ($handle === false) {
            throw new \RuntimeException("Unable to open file: {$this->jsonPath}");
        }

        try {
            while (($line = fgets($handle)) !== false) {
                $transaction = json_decode($line, true);
                
                if ($this->passesFilters($transaction)) {
                    yield $this->transformToDTO($transaction);
                }
            }
        } finally {
            fclose($handle);
        }
    }

    private function transformToDTO(array $data): UserTransactionDTO
    {
        return new UserTransactionDTO(
            amount: $data['parentAmount'],
            currency: $data['Currency'],
            email: $data['parentEmail'],
            status: TransactionStatus::fromProviderXCode($data['statusCode'])->value,
            date: $data['registerationDate'],
            identifier: $data['parentIdentification'],
            provider: $this->getName()
        );
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
                    if (TransactionStatus::fromProviderXCode($transaction['statusCode'])->value !== $value) {
                        return false;
                    }
                    break;
                case 'currency':
                    if ($transaction['Currency'] !== $value) {
                        return false;
                    }
                    break;
                case 'balanceMin':
                    if ($transaction['parentAmount'] < $value) {
                        return false;
                    }
                    break;
                case 'balanceMax':
                    if ($transaction['parentAmount'] > $value) {
                        return false;
                    }
                    break;
            }
        }
        return true;
    }
}

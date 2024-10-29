<?php

namespace App\Services\DataProviders;

use App\DTOs\UserTransactionDTO;
use App\Enums\TransactionStatus;

class DataProviderX implements DataProviderInterface
{
    private array $filters = [];
    private const JSON_PATH = 'storage/app/DataProviderX.json';

    public function getName(): string
    {
        return 'DataProviderX';
    }

    public function getTransactions(): \Generator
    {
        $handle = fopen(self::JSON_PATH, 'r');
        
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

<?php

namespace Tests\Unit\Services\DataProviders;

use Tests\TestCase;
use App\Services\DataProviders\DataProviderX;

class DataProviderXTest extends TestCase
{
    private DataProviderX $provider;

    protected function setUp(): void
    {
        parent::setUp();
        $this->provider = new DataProviderX();
    }

    public function test_it_filters_by_status()
    {
        $filters = ['status' => 'authorised'];
        $transactions = iterator_to_array($this->provider->applyFilters($filters)->getTransactions());

        foreach ($transactions as $transaction) {
            $this->assertEquals('authorised', $transaction->status);
        }
    }

    public function test_it_filters_by_amount_range()
    {
        $filters = [
            'balanceMin' => 100,
            'balanceMax' => 200
        ];
        
        $transactions = iterator_to_array($this->provider->applyFilters($filters)->getTransactions());

        foreach ($transactions as $transaction) {
            $this->assertGreaterThanOrEqual(100, $transaction->amount);
            $this->assertLessThanOrEqual(200, $transaction->amount);
        }
    }
} 
<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\NoSQL;

use App\Domain\Repositories\Contracts\CryptoRatesRepositoryInterface;

class CryptoRatesRepository implements CryptoRatesRepositoryInterface
{
    public function getRates(): array
    {
        return [
            'Bitcoin' => 50000,
            'Ethereum' => 4000,
        ];
    }
}
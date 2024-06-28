<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\NoSQL;

use App\Domain\Repositories\Contracts\BalanceRepositoryInterface;

class BalanceRepository implements BalanceRepositoryInterface
{
    public function getBalance(): float
    {
        return 1000;
    }
}
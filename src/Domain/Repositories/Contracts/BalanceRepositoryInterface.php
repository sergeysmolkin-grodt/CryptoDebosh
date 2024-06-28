<?php

namespace App\Domain\Repositories\Contracts;

interface BalanceRepositoryInterface
{
    public function getBalance(): float;

}
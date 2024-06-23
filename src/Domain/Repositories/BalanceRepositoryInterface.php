<?php

namespace App\Domain\Repositories;

interface BalanceRepositoryInterface
{
    public function getBalance(): float;

}
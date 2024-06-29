<?php

namespace App\Domain\Repositories\Contracts;

interface CryptoRatesRepositoryInterface
{
    public function getRates(): array;
}
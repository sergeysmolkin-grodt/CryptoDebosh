<?php

namespace App\Domain\Repositories;

interface CryptoRatesRepositoryInterface
{
    public function getRates(): array;
}
<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Repositories\BalanceRepositoryInterface;
use App\Domain\Repositories\CryptoRatesRepositoryInterface;

class CryptoService
{
    private CryptoRatesRepositoryInterface $cryptoRatesRepository;
    private BalanceRepositoryInterface $balanceRepository;

    public function __construct(
        CryptoRatesRepositoryInterface $cryptoRatesRepository,
        BalanceRepositoryInterface $balanceRepository
    ) {
        $this->cryptoRatesRepository = $cryptoRatesRepository;
        $this->balanceRepository = $balanceRepository;
    }

    public function getCryptoRates(): array
    {
        return $this->cryptoRatesRepository->getRates();
    }

    public function getBalance(): float
    {
        return $this->balanceRepository->getBalance();
    }
}
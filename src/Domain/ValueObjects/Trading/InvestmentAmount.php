<?php

declare(strict_types=1);

namespace App\Domain\ValueObjects\Trading;

final readonly class InvestmentAmount
{

    /**
     * @var float;
     */
    private float $amount;

    /**
     * @param float $amount
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(float $amount)
    {
        if($amount <= 0) {
            throw new \InvalidArgumentException('Investment amount must be greater than zero.');
        }

        $this->amount = $amount;
    }


    /**
     * Get the investment amount.
     *
     * @return float
     */
    public function get(): float
    {
        return $this->amount;
    }

    /**
     * Increase the investment amount by a given value.
     *
     * @param float $amount
     * @return self
     * @throws \InvalidArgumentException
     */
    public function increase(float $amount): self
    {
        if($amount <= 0) {
            throw new \InvalidArgumentException('Increase amount must be greater than zero.');
        }

        $this->amount += $this->amount;
        return $this;
    }

    /**
     * Decrease the investment amount by a given value.
     *
     * @param float $amount
     * @return self
     * @throws \InvalidArgumentException
     */
    public function decrease(float $amount): self
    {
        if ($amount <= 0) {
            throw new \InvalidArgumentException('Decrease amount must be greater than zero.');
        }

        if ($amount > $this->amount) {
            throw new \InvalidArgumentException('Decrease amount cannot be greater than the current investment amount.');
        }

        $this->amount -= $amount;
        return $this;
    }

    /**
     * Convert the investment amount to a string representation.
     *
     * @return string
     */
    public function __toString(): string
    {
        return (string) $this->amount;
    }

}
<?php

declare(strict_types=1);

namespace App\Application\DataTransferObjects\User;

class UserDTO
{
    private int $id;
    private string $name;
    private string $email;
    private float $balance;
    private string $address;
    private array $roles;
    private \DateTime $createdAt;
    private array $transactionHistory;

    public function __construct(
        int $id,
        string $name,
        string $email,
        float $balance,
        string $address,
        array $roles,
        \DateTime $createdAt,
        array $transactionHistory
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->balance = $balance;
        $this->address = $address;
        $this->roles = $roles;
        $this->createdAt = $createdAt;
        $this->transactionHistory = $transactionHistory;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    public function getTransactionHistory(): array
    {
        return $this->transactionHistory;
    }
}
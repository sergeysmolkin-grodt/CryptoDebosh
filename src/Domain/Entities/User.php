<?php

namespace App\Domain\Entities;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\Doctrine\UserRepository")
 * @ORM\Table(name="users")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private string $name;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private string $email;

    /**
     * @ORM\Column(type="float")
     */
    private float $balance;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $address;

    /**
     * @ORM\Column(type="json")
     */
    private array $roles = [];

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="json")
     */
    private array $transactionHistory = [];

    public function __construct(string $name, string $email, float $balance, string $address, array $roles, DateTime $createdAt)
    {
        $this->name = $name;
        $this->email = $email;
        $this->balance = $balance;
        $this->address = $address;
        $this->roles = $roles;
        $this->createdAt = $createdAt;
        $this->transactionHistory = [];
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

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getTransactionHistory(): array
    {
        return $this->transactionHistory;
    }

    public function setBalance(float $balance): void
    {
        $this->balance = $balance;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function increaseBalance(float $amount): void
    {
        $this->balance += $amount;
        $this->addTransaction('credit', $amount);
    }

    public function decreaseBalance(float $amount): void
    {
        if ($amount > $this->balance) {
            throw new \Exception('Insufficient balance.');
        }
        $this->balance -= $amount;
        $this->addTransaction('debit', $amount);
    }

    private function addTransaction(string $type, float $amount): void
    {
        $this->transactionHistory[] = [
            'type' => $type,
            'amount' => $amount,
            'date' => new DateTime()
        ];
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }
    }

    public function removeRole(string $role): void
    {
        $this->roles = array_filter($this->roles, fn($r) => $r !== $role);
    }

    public function getFullName(): string
    {
        return $this->name;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles, true);
    }
}

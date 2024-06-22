<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence\Doctrine;

use App\Domain\Entities\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     *
     * @return User[]
     */
    public function findAllAdmins(): array
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :roles')
            ->setParameter('roles', '%ROLE_ADMIN%')
            ->getQuery()
            ->getResult();
    }

    /**
     *
     * @param int $userId
     * @param float $amount
     */
    public function updateBalance(int $userId, float $amount): void
    {
        $entityManager = $this->getEntityManager();
        $user = $entityManager->getRepository(User::class)->find($userId);

        if ($user) {
            $user->setBalance($amount);
            $entityManager->flush();
        }
    }

    /**
     *
     * @return User[]
     */
    public function findAllUsers(): array
    {
        return $this->createQueryBuilder('u')
            ->getQuery()
            ->getResult();
    }

    /**
     *
     * @param User $user
     */
    public function add(User $user): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
    }

    /**
     *
     * @param int $userId
     */
    public function deleteById(int $userId): void
    {
        $entityManager = $this->getEntityManager();
        $user = $entityManager->getRepository(User::class)->find($userId);

        if ($user) {
            $entityManager->remove($user);
            $entityManager->flush();
        }
    }
}
<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\ORM;

use App\Domain\Entities\CryptoAsset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class CryptoAssetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CryptoAsset::class);
    }
}
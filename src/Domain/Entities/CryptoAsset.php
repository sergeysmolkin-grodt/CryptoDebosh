<?php

declare(strict_types=1);

namespace App\Domain\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Repository\CryptoAssetRepository")
 * @ORM\Table(name="crypto_assets")
 */
class CryptoAsset
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $symbol;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=8)
     */
    private $open;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=8)
     */
    private $high;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=8)
     */
    private $low;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=8)
     */
    private $close;

    /**
     * @ORM\Column(type="decimal", precision=18, scale=8)
     */
    private $volume;

    // Getters and Setters...
}

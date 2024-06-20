<?php

declare(strict_types=1);

namespace CryptoDebosh\Application\Services;

use CryptoDebosh\Domain\Entities\Blockchain;
use CryptoDebosh\Domain\Entities\Block;

class BlockchainService
{
    private Blockchain $blockchain;

    public function __construct()
    {
        $this->blockchain = new Blockchain();
    }

    public function addNewBlock($data): void
    {
        $newBlock = new Block(count($this->blockchain->chain), time(), $data);
        $this->blockchain->addBlock($newBlock);
    }

    public function getBlockchain(): array
    {
        return $this->blockchain->chain;
    }

    public function isBlockchainValid(): bool
    {
        return $this->blockchain->isChainValid();
    }
}
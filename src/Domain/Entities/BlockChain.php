<?php

declare(strict_types=1);

namespace CryptoDebosh\Domain\Entities;

class Blockchain
{
    public $chain;
    public $difficulty;

    public function __construct()
    {
        $this->chain = [$this->createGenesisBlock()];
        $this->difficulty = 4;
    }

    private function createGenesisBlock()
    {
        return new Block(0, strtotime('2024-01-01'), 'Genesis Block', '0');
    }

    public function getLatestBlock()
    {
        return end($this->chain);
    }

    public function addBlock($newBlock)
    {
        $newBlock->previousHash = $this->getLatestBlock()->hash;
        $newBlock->mineBlock($this->difficulty);
        $this->chain[] = $newBlock;
    }

    public function isChainValid()
    {
        //for better optimization
        //$iMax = count($this->chain); $i < $iMax;
        for ($i = 1; $i < count($this->chain); $i++) {
            $currentBlock = $this->chain[$i];
            $previousBlock = $this->chain[$i - 1];

            if ($currentBlock->hash !== $currentBlock->calculateHash()) {
                return false;
            }

            if ($currentBlock->previousHash !== $previousBlock->hash) {
                return false;
            }
        }

        return true;
    }
}
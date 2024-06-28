<?php

declare(strict_types=1);

namespace App\Domain\Services;

use App\Domain\Entities\CryptoAsset;
use App\Infrastructure\Repositories\ORM\CryptoAssetRepository;

class CryptoAssetService
{
    private $repository;

    public function __construct(CryptoAssetRepository $repository)
    {
        $this->repository = $repository;
    }

    public function saveAssetData(array $data)
    {
        foreach ($data as $entry) {
            $cryptoAsset = new CryptoAsset();
            $cryptoAsset->setSymbol($entry['symbol']);
            $cryptoAsset->setTimestamp((new \DateTime())->setTimestamp($entry['timestamp'] / 1000));
            $cryptoAsset->setOpen($entry['open']);
            $cryptoAsset->setHigh($entry['high']);
            $cryptoAsset->setLow($entry['low']);
            $cryptoAsset->setClose($entry['close']);
            $cryptoAsset->setVolume($entry['volume']);

            $this->repository->save($cryptoAsset);
        }

        $this->repository->flush();
    }
}
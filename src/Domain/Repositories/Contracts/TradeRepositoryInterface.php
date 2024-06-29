<?php

namespace App\Domain\Repositories\Contracts;

use App\Domain\Entities\Trade;

interface TradeRepositoryInterface
{
    public function save(Trade $trade): void;

}
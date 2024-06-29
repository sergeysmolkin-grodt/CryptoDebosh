<?php

declare(strict_types=1);

namespace App\Infrastructure\Repositories\ORM;


use App\Domain\Repositories\Contracts\TradeRepositoryInterface;

class TradeRepository implements TradeRepositoryInterface
{
    public function save(\App\Domain\Entities\Trade $trade): void
    {
        // Сохранение сделки в базу данных или другом хранилище
    }
}
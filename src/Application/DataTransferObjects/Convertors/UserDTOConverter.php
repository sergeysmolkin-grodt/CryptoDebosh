<?php

declare(strict_types=1);

namespace App\Application\DataTransferObjects\Convertors;

use App\Application\DataTransferObjects\User\UserDTO;
use App\Entity\User;

class UserDTOConverter
{
    public static function convert(User $user): UserDTO
    {
        return new UserDTO(
            $user->getId(),
            $user->getName(),
            $user->getEmail(),
            $user->getBalance(),
            $user->getAddress(),
            $user->getRoles(),
            $user->getCreatedAt(),
            $user->getTransactionHistory()
        );
    }
}
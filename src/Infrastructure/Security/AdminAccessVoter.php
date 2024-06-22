<?php

declare(strict_types=1);

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Symfony\Component\Security\Core\User\UserInterface;

class AdminAccessVoter
{
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['ROLE_ADMIN']);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof UserInterface) {
            return false;
        }

        return in_array('ROLE_ADMIN', $user->getRoles());
    }
}
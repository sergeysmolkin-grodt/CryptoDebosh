<?php

namespace App\Infrastructure\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class AdminAccessVoter implements VoterInterface
{
    private const ADMIN_ACCESS = 'ADMIN_ACCESS';

    public function supportsAttribute(string $attribute): bool
    {
        return $attribute === self::ADMIN_ACCESS;
    }

    public function supportsType(string $subjectType): bool
    {
        return true;
    }

    public function vote(TokenInterface $token, $subject, array $attributes): int
    {
        foreach ($attributes as $attribute) {
            if (!$this->supportsAttribute($attribute)) {
                continue;
            }

            $user = $token->getUser();

            if (!$user instanceof UserInterface) {
                return VoterInterface::ACCESS_DENIED;
            }

            return in_array('ROLE_ADMIN', $user->getRoles(), true)
                ? VoterInterface::ACCESS_GRANTED
                : VoterInterface::ACCESS_DENIED;
        }

        return VoterInterface::ACCESS_ABSTAIN;
    }
}

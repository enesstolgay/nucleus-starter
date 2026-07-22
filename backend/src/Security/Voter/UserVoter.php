<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Enum\UserRole;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW], true)
            && $subject instanceof User;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        if ($this->security->isGranted(UserRole::ADMIN->value)) {
            return true;
        }

        $currentUser = $token->getUser();
        if (!$currentUser instanceof User) {
            return false;
        }

        /** @var User $subject */
        return $currentUser->getId() === $subject->getId();
    }
}

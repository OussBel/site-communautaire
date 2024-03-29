<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserConfirmation implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isIsVerified()) {
            throw new CustomUserMessageAccountStatusException("Votre compte n'est pas encore vérifié,
             merci de confirmer votre adresse émail avant le {$user->getTokenRegistrationLifeTime()
             ->format('d/m/y à H\hi')} ");
        }
    }
}

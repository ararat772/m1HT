<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;

class UserService extends AbstractService
{
    public function createUser(string $name): User
    {
        $user = new User();
        $user->setName($name);
        $user->setRole('user');

        $this->saveUser($user);
        return $user;
    }

    public function saveUser(User $user): void
    {
        $this->em->persist($user);
        $this->em->flush();
    }
}

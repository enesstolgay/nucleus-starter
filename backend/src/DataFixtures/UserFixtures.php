<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Enum\UserRole;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('admin@nucleus.dev');
        $admin->setRoles([UserRole::ADMIN->value]);
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin123')
        );
        $manager->persist($admin);

        $user = new User();
        $user->setEmail('user@nucleus.dev');
        $user->setRoles([UserRole::USER->value]);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'user123')
        );
        $manager->persist($user);

        $manager->flush();
    }
}
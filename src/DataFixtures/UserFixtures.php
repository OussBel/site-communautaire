<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{

public function __construct(private readonly UserPasswordHasherInterface $hasher)
{
}

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $user = new User();

            $user->setName($faker->name())
                ->setEmail($faker->unique()->email())
                ->setImage('empty.png')
                ->setIsVerified(true)
                ->setRoles(['ROLE_USER']);

            $password = $this->hasher->hashPassword($user, 'pass_1234');
            $user->setPassword($password);

            $manager->persist($user);
        }

        $manager->flush();
    }
}

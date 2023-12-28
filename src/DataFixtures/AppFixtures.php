<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use App\Entity\Trick;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Faker\Factory;

class AppFixtures extends Fixture implements DependentFixtureInterface
{

    public function __construct( private readonly SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = $manager->getRepository(User::class)->findAll();
        $groups = $manager->getRepository(Groupe::class)->findAll();

        foreach ($users as $user) {
            for ($i = 0; $i < 7; $i++) {
                $trick = new Trick();
                $trick->setName($faker->unique()->word());
                $slug = strtolower($this->slugger->slug($trick->getName()));
                $trick->setSlug($slug)->setDescription($faker->paragraph(7));

                $randomGroupId = array_rand($groups);
                $randomGroupValue = $groups[$randomGroupId];
                $trick->setUser($user)->setGroupe($randomGroupValue);

                $manager->persist($trick);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            GroupeFixtures::class,
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Groupe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GroupeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $groupList = ['Grabs', 'Rotations', 'Flips',
            'Slides', 'Butters', 'Bonks', 'Spins désaxés',
            'Slopestyle', 'handplants', 'Combos jibbing'];

        for ($i = 0; $i < 10; $i++) {
            $group = new Groupe();
            $group->setName($groupList[$i]);
            $manager->persist($group);
        }

        $manager->flush();
    }
}

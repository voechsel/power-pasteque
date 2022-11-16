<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $userList = [
            ['name' => 'Vincent', 'matos' => 'PC', 'haircolor' => 'Brun'],
            ['name' => 'Audrey', 'matos' => 'Mac', 'haircolor' => 'Noir'],
            ['name' => 'Antony', 'matos' => 'Mac', 'haircolor' => 'Brun']
        ];

        foreach ($userList as $value) {
            $user = new User();
            $user->setName($value['name']);
            $user->setMatos($value['matos']);
            $user->setHaircolor($value['haircolor']);
            $manager->persist($user);
        }
        $manager->flush();
    }
}

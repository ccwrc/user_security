<?php

namespace App\DataFixtures;

use App\Entity\User;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i < 10; $i++) {
            $user = new User();
            $user->setEmail('ccwrc' . $i . '@morsem.pl')
                ->setPassword('ccwrc' . $i);
            $manager->persist($user);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    public function __construct(private UserPasswordHasherInterface  $hasher) {
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        $faker = Factory::create('fr_FR');

        $user = new User();
        $user->setEmail('camile@camile.fr')
            ->setPassword($this->hasher->hashPassword($user, 'camile'));

        $manager->persist($user);

        // Creation of fake articles
        for ($i = 0; $i < 10; ++$i) {
            $user = new User();

            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'password'));

            $manager->persist($user);
        }

        $manager->flush();
    }
}
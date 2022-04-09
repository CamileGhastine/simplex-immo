<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    const NB_POSTS = 10;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for ($j = 0; $j < self::NB_POSTS; ++$j) {
            $post = new Post();

            $date = $faker->dateTimebetween('-7 days');

            $post->setTitle(substr($faker->sentence(3, true), 0, 29))
                ->setContent(implode("\n", $faker->sentences(4)))
                ->setCreatedAt($date)
                ->setUpdatedAt($date)
            ;

            $manager->persist($post);
        }
        $manager->flush();
    }
}

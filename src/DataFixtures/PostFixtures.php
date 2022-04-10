<?php

namespace App\DataFixtures;

use App\Entity\Media;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    const NB_POSTS = 20;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        // Creatation og fake articles
        for ($i = 0; $i < self::NB_POSTS; ++$i) {
            $post = new Post();

            $date = $faker->dateTimebetween('-7 days');

            $post->setTitle(substr($faker->sentence(3, true), 0, 29))
                ->setContent(implode("\n", $faker->sentences(100)))
                ->setCreatedAt($date)
                ->setUpdatedAt($date);

            //  Creation of fake medias
            for ($j = 0; $j < rand(0, 3); $j++) {

                if ($i % 5 === 0) continue;

                $media = new Media();

                $media->setTitle(substr($faker->sentence(3, true), 0, 29))
                    ->setSrc("https://picsum.photos/300/3" . $j . rand(0, 9))
                    ->setType("image")
                    ->setPoster(false);
                if ($j === 0) $media->setPoster(true);

                $post->addMedia($media);

                $manager->persist($media);
            }
            $manager->persist($post);
        }
        $manager->flush();
    }
}

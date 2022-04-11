<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Image;
use App\Entity\Post;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class PostFixtures extends Fixture
{
    const NB_POSTS = 21;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        $faker = Factory::create('fr_FR');

        //Creation of fake categories
        $categories = [];
        $titles = ["Actualité", "Reportage", "Tutoriel"];

        foreach ($titles as $title) {
            $category = new Category();
            $category->setTitle($title);

            $categories[] = $category;

            $manager->persist($category);
        }

        // Creation of fake articles
        for ($i = 0; $i < self::NB_POSTS; ++$i) {
            $post = new Post();

            $date = $faker->dateTimebetween('-100 days');

            $post->setTitle($faker->sentence(3, true))
                ->setContent(implode("\n", $faker->sentences(100)))
                ->setCreatedAt($date)
                ->setUpdatedAt($date);

            //  Creation of fake images
            for ($j = 0; $j < rand(0, 3); $j++) {

                if ($i % 5 === 0)
                    continue;

                $image = new Image();

                $image->setTitle(substr($faker->sentence(3, true), 0, 29))
                    ->setSrc("https://picsum.photos/300/3" . $j . rand(0, 9))
                    ->setPoster(false);
                if ($j === 0)
                    $image->setPoster(true);

                $post->addImage($image);

                $manager->persist($image);


            }

            //  Creation of fake videos
            $videos = [
                'https://www.youtube.com/embed/cWhIjM8fjr0',
                'https://www.youtube.com/embed/kvNxlyNFfOk',
                'https://www.youtube.com/embed/9itOLVl3dLA'
            ];
            for ($j = 0; $j < rand(0, 2); $j++) {

                if ($i % 5 === 0)
                    continue;

                $video = new Video();

                $video->setTitle(substr($faker->sentence(3, true), 0, 29))
                    ->setUrl($videos[rand(0, 2)]);

                $post->addVideo($video);

                $manager->persist($video);
            }

            $post->setCategory($categories[rand(0, count($categories) - 1)]);

            $manager->persist($post);
        }
        $manager->flush();
    }
}

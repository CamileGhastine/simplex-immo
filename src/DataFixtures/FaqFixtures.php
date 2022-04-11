<?php

namespace App\DataFixtures;

use App\Entity\Faq;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FaqFixtures extends Fixture
{

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager) {
        $faker = Factory::create('fr_FR');

        // Creation of fake articles
        for ($i = 0; $i < 10; ++$i) {
            $faq = new Faq();

            $date = $faker->dateTimebetween('-100 days');

            $faq->setQuestion(substr($faker->sentence(3, true), 0, -1) . ' ?')
                ->setAnswer(implode("\n", $faker->sentences(100)))
                ->setCreatedAt($date)
                ->setUpdatedAt($date);

            $manager->persist($faq);
        }
        $manager->flush();
    }
}

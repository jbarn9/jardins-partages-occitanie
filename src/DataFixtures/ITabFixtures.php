<?php

namespace App\DataFixtures;

use App\Entity\Tabs;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ITabFixtures extends Fixture
{
    public const TAB_REFERENCE_TAG = 'tab-';
    public const TAB_COUNT = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < self::TAB_COUNT; $i++) {
            $tab = new Tabs();
            $tab->setLabel($faker->unique()->word);
            $tab->setNewsFeed($faker->boolean);
            $manager->persist($tab);
            $this->addReference(self::TAB_REFERENCE_TAG . $i, $tab);
        }
        $manager->flush();
    }
}

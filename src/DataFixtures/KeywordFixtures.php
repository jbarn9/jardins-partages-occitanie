<?php

namespace App\DataFixtures;

use App\Entity\Keywords;
use App\Entity\Posts;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class KeywordFixtures extends Fixture implements DependentFixtureInterface
{
    public const KEYWORD_REFERENCE_TAG = 'keyword-';
    public const KEYWORD_COUNT = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        // $product = new Product();
        // $manager->persist($product);
        for ($i = 0; $i < self::KEYWORD_COUNT; $i++) {
            $keyword = new Keywords();
            $keyword->setLabel($faker->unique()->word);
            $keyword->addKeywordsPost($this->getReference(JPostFixtures::POST_REFERENCE_TAG . rand(0, JPostFixtures::POST_COUNT - 1), Posts::class));
            $manager->persist($keyword);
            $this->addReference(self::KEYWORD_REFERENCE_TAG . $i, $keyword);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            JPostFixtures::class
        ];
    }
}

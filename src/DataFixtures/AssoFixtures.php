<?php

namespace App\DataFixtures;

use App\Entity\Association;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AssoFixtures extends Fixture
{
    public const ASSO_REFERENCE_TAG = 'asso-';
    public const ASSO_COUNT = 1;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::ASSO_COUNT; $i++) {
            $faker = Factory::create('fr_FR');
            // Create the admin user
            $asso = new Association();
            $asso->setName($faker->unique()->userName);
            $asso->setMantra($faker->text(100));
            $asso->setLucrative($faker->boolean);
            $asso->setMobile($faker->unique()->phoneNumber);
            $asso->setDescription($faker->firstName);
            $asso->setBanner($faker->imageUrl(640, 480, 'network'));
            $asso->setLogo($faker->imageUrl(640, 480, 'network'));
            $asso->setFoundedAt(new \DateTimeImmutable());
            $asso->setCreatedAt(new \DateTimeImmutable());
            $asso->setEmail($faker->unique()->email());
            $manager->persist($asso);
            $this->addReference(self::ASSO_REFERENCE_TAG . $i, $asso);
        }
        $manager->flush();
    }
}

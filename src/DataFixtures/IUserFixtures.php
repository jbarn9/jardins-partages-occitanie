<?php

namespace App\DataFixtures;

use App\Entity\Association;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class IUserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USER_REFERENCE_TAG = 'user-';
    public const USER_COUNT = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < self::USER_COUNT; $i++) {
            $user = new User();
            $user->setLogin($faker->unique()->userName);
            $user->setToken($faker->unique()->uuid);
            $user->setTokenExpirateAt(new \DateTimeImmutable('+1 hour'));
            $user->setFirstname($faker->firstName);
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setLastname($faker->lastName);
            $user->setMobile($faker->unique()->phoneNumber);
            $user->setImg($faker->imageUrl(640, 480, 'people'));
            $user->setRoles(['ROLE_EDITOR']);
            $user->setPassword(password_hash('pwd' . $user->getLogin(), PASSWORD_BCRYPT));
            $user->setEmail($faker->unique()->email());

            $user->setUserAsso($this->getReference(AssoFixtures::ASSO_REFERENCE_TAG . rand(0, AssoFixtures::ASSO_COUNT - 1), Association::class));

            $manager->persist($user);
            $this->addReference(self::USER_REFERENCE_TAG . $i, $user);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            AssoFixtures::class
        ];
    }
}

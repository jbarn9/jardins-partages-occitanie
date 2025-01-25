<?php

namespace App\DataFixtures;

use App\Entity\Keywords;
use App\Entity\Posts;
use App\Entity\Tabs;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class JPostFixtures extends Fixture implements DependentFixtureInterface
{
    public const POST_REFERENCE_TAG = 'post-';
    public const POST_COUNT = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < self::POST_COUNT; $i++) {
            $post = new Posts();
            $post->setTitle($faker->unique()->text);
            $post->setContent($faker->text(400));
            $post->setPostedAt(new \DateTimeImmutable());
            $post->setSlug($faker->slug(2));
            $post->setStatus($faker->randomElement(['draft', 'published', 'archived']));
            $post->setLikesCounter($faker->numberBetween(0, 100));
            $post->setCommentCounter($faker->numberBetween(0, 100));
            $post->setTabs($this->getReference(ITabFixtures::TAB_REFERENCE_TAG . rand(0, ITabFixtures::TAB_COUNT - 1), Tabs::class));
            $post->setUser($this->getReference(IUserFixtures::USER_REFERENCE_TAG . rand(0, IUserFixtures::USER_COUNT - 1), User::class));
            // $post->addKeyword($this->getReference(KeywordFixtures::KEYWORD_REFERENCE_TAG . rand(0, KeywordFixtures::KEYWORD_COUNT - 1), Keywords::class));
            $manager->persist($post);
            $this->addReference(self::POST_REFERENCE_TAG . $i, $post);
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            // KeywordFixtures::class,
            ITabFixtures::class,
            IUserFixtures::class,
        ];
    }
}

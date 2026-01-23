<?php

namespace App\DataFixtures;

use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) { 
            $post = new Post();
            $post->setTitle("prueba fixture " . ($i + 1));
            $post->setSlug("slugFixture" . ($i + 1));
            $post->setPublishedAt(new \DateTime());
            $manager->persist($post);
        }

        $manager->flush();
    }
}

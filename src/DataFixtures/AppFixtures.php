<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Crear usuarios
        $users = [];
        $usersData = [
            ['Juan', 'Pérez', 'juan@example.com'],
            ['María', 'García', 'maria@example.com'],
            ['Carlos', 'López', 'carlos@example.com'],
        ];
        
        foreach ($usersData as $userData) {
            $user = new User();
            $user->setFirstName($userData[0]);
            $user->setLastName($userData[1]);
            $user->setEmail($userData[2]);
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));
            $user->setIsVerified(true);
            $manager->persist($user);
            $users[] = $user;
        }

        // Crear categorías
        $categories = [];
        $categoryNames = ['Tecnología', 'Deportes', 'Cultura', 'Ciencia', 'Viajes'];
        
        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Crear posts con autores
        for ($i = 0; $i < 3; $i++) { 
            $post = new Post();
            $post->setTitle("prueba fixture " . ($i + 1));
            $post->setSlug("slugFixture" . ($i + 1));
            $post->setPublishedAt(new \DateTime());
            
            // Asignar un autor aleatorio
            if (!empty($users)) {
                $post->setAuthor($users[array_rand($users)]);
            }
            
            // Asignar 1-3 categorías aleatorias
            if (!empty($categories)) {
                $numCategories = rand(1, 3);
                $randomKeys = array_rand($categories, min($numCategories, count($categories)));
                if (!is_array($randomKeys)) {
                    $randomKeys = [$randomKeys];
                }
                foreach ($randomKeys as $key) {
                    $post->addCategory($categories[$key]);
                }
            }
            $manager->persist($post);
        }

        $manager->flush();
    }
}

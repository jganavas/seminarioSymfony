<?php

namespace App\DataFixtures;

use App\Entity\Administrator;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Writer;
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
        $users = [];

        // Create Writers with different specialties
        $writersData = [
            ['Juan', 'Pérez', 'juan@example.com', 'Symfony', 'Experto en desarrollo backend con Symfony'],
            ['María', 'García', 'maria@example.com', 'Frontend', 'Especialista en React y diseño de interfaces'],
            ['Carlos', 'López', 'carlos@example.com', 'DevOps', 'Ingeniero de infraestructura y automatización'],
            ['Ana', 'Martínez', 'ana@example.com', 'Diseño', 'Diseñadora UI/UX con pasión por la accesibilidad'],
            ['Pedro', 'Sánchez', 'pedro@example.com', 'Full Stack', 'Desarrollador Full Stack con experiencia en múltiples tecnologías'],
        ];

        foreach ($writersData as $writerData) {
            $writer = new Writer();
            $writer->setFirstName($writerData[0]);
            $writer->setLastName($writerData[1]);
            $writer->setEmail($writerData[2]);
            $writer->setSpecialty($writerData[3]);
            if (isset($writerData[4])) {
                $writer->setBio($writerData[4]);
            }
            $writer->setPassword($this->passwordHasher->hashPassword($writer, 'password123'));
            $writer->setIsVerified(true);
            $manager->persist($writer);
            $users[] = $writer;
        }

        // Create Administrators with different permission levels
        $adminsData = [
            ['Admin', 'Principal', 'admin@example.com', 5, 'Administrador principal del sistema'],
            ['Pepe', 'Navas', 'jganavas@gmail.com', 5, 'Superadmin del sistema', 'Contraseña123'],
            ['Moderador', 'Contenido', 'moderator@example.com', 3, 'Moderador de contenido y publicaciones'],
        ];

        foreach ($adminsData as $adminData) {
            $admin = new Administrator();
            $admin->setFirstName($adminData[0]);
            $admin->setLastName($adminData[1]);
            $admin->setEmail($adminData[2]);
            $admin->setPermissionLevel($adminData[3]);
            if (isset($adminData[4])) {
                $admin->setBio($adminData[4]);
            }
            
            // Password logic: use provided password or default 'admin123'
            $password = isset($adminData[5]) ? $adminData[5] : 'admin123';
            $admin->setPassword($this->passwordHasher->hashPassword($admin, $password));
            
            $admin->setRoles(['ROLE_ADMIN']);
            $admin->setIsVerified(true);
            $manager->persist($admin);
            $users[] = $admin;
        }

        // Create specific Standard Users for testing
        // 1. Verified User
        $userVerified = new Writer(); // Using Writer as base for standard user since User is abstract
        $userVerified->setFirstName('Usuario');
        $userVerified->setLastName('Verificado');
        $userVerified->setEmail('user@user.com');
        $userVerified->setSpecialty('Lector');
        $userVerified->setBio('Usuario estándar verificado.');
        $userVerified->setPassword($this->passwordHasher->hashPassword($userVerified, 'password123'));
        $userVerified->setIsVerified(true);
        $manager->persist($userVerified);
        $users[] = $userVerified;

        // 2. Unverified User
        $userUnverified = new Writer();
        $userUnverified->setFirstName('Usuario');
        $userUnverified->setLastName('No Verificado');
        $userUnverified->setEmail('unverified@user.com');
        $userUnverified->setSpecialty('Nuevo');
        $userUnverified->setBio('Usuario registrado pero no verificado.');
        $userUnverified->setPassword($this->passwordHasher->hashPassword($userUnverified, 'password123'));
        $userUnverified->setIsVerified(false);
        $manager->persist($userUnverified);
        // Note: Not adding to $users array to avoid them being authors of posts

        // 3. Editor User
        $editor = new Writer();
        $editor->setFirstName('Editor');
        $editor->setLastName('Jefe');
        $editor->setEmail('editor@editor.com');
        $editor->setSpecialty('Edición');
        $editor->setBio('Editor jefe con permisos de escritura.');
        $editor->setPassword($this->passwordHasher->hashPassword($editor, 'password123'));
        $editor->setIsVerified(true);
        $manager->persist($editor);
        $users[] = $editor;

        // Create categories
        $categories = [];
        $categoryNames = ['Tecnología', 'Deportes', 'Cultura', 'Ciencia', 'Viajes', 'Tutorial', 'Opinión'];

        foreach ($categoryNames as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            $categories[] = $category;
        }

        // Create posts authored by Writers
        $postsData = [
            ['Introducción a Symfony 7', 'introduccion-symfony-7', 'Descubre las nuevas características de Symfony 7 y cómo empezar a usarlo', 0],
            ['Diseño de componentes reutilizables', 'diseño-componentes-reutilizables', 'Guía completa para crear componentes UI reutilizables y mantenibles', 1],
            ['CI/CD con GitHub Actions', 'ci-cd-github-actions', 'Automatiza tu flujo de trabajo con pipelines de integración continua', 2],
            ['Principios de diseño UX', 'principios-diseño-ux', 'Los fundamentos del diseño centrado en el usuario', 3],
            ['Arquitectura de microservicios', 'arquitectura-microservicios', 'Cómo diseñar y implementar una arquitectura de microservicios escalable', 4],
            ['Optimización de rendimiento en Symfony', 'optimizacion-rendimiento-symfony', 'Técnicas avanzadas para mejorar el rendimiento de tus aplicaciones Symfony', 0],
        ];

        foreach ($postsData as $index => $postData) {
            $post = new Post();
            $post->setTitle($postData[0]);
            $post->setSlug($postData[1]);
            $post->setSummary($postData[2]);
            $post->setContent($postData[2] . '. Este es un artículo completo sobre el tema.');
            $post->setPublishedAt(new \DateTime('-' . ($index * 2) . ' days'));

            // Assign to a Writer (not an Administrator)
            $writerIndex = $postData[3];
            if ($writerIndex < count($writersData)) {
                $post->setAuthor($users[$writerIndex]);
            }

            // Assign 1-3 random categories
            $numCategories = rand(1, 3);
            $randomKeys = array_rand($categories, min($numCategories, count($categories)));
            if (!is_array($randomKeys)) {
                $randomKeys = [$randomKeys];
            }
            foreach ($randomKeys as $key) {
                $post->addCategory($categories[$key]);
            }

            $manager->persist($post);
        }

        $manager->flush();
    }
}

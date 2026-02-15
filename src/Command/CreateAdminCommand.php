<?php

namespace App\Command;

use App\Entity\Administrator;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates a new admin user',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $io->title('🔐 Crear Usuario Administrador');

        // Email
        $emailQuestion = new Question('Email del administrador: ');
        $emailQuestion->setValidator(function ($answer) {
            if (!filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Email inválido');
            }
            
            // Verificar si el email ya existe
            if ($this->userRepository->findOneBy(['email' => $answer])) {
                throw new \RuntimeException('Este email ya está registrado');
            }
            
            return $answer;
        });
        $email = $helper->ask($input, $output, $emailQuestion);

        // Nombre
        $firstNameQuestion = new Question('Nombre: ');
        $firstNameQuestion->setValidator(function ($answer) {
            if (empty(trim($answer))) {
                throw new \RuntimeException('El nombre no puede estar vacío');
            }
            return trim($answer);
        });
        $firstName = $helper->ask($input, $output, $firstNameQuestion);

        // Apellidos
        $lastNameQuestion = new Question('Apellidos: ');
        $lastNameQuestion->setValidator(function ($answer) {
            if (empty(trim($answer))) {
                throw new \RuntimeException('Los apellidos no pueden estar vacíos');
            }
            return trim($answer);
        });
        $lastName = $helper->ask($input, $output, $lastNameQuestion);

        // Nivel de permisos
        $permissionQuestion = new Question('Nivel de permisos (1-5, donde 5 es super admin) [5]: ', '5');
        $permissionQuestion->setValidator(function ($answer) {
            $level = (int) $answer;
            if ($level < 1 || $level > 5) {
                throw new \RuntimeException('El nivel de permisos debe estar entre 1 y 5');
            }
            return $level;
        });
        $permissionLevel = $helper->ask($input, $output, $permissionQuestion);

        // Contraseña
        $passwordQuestion = new Question('Contraseña: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setHiddenFallback(false);
        $passwordQuestion->setValidator(function ($answer) {
            if (strlen($answer) < 8) {
                throw new \RuntimeException('La contraseña debe tener al menos 8 caracteres');
            }
            return $answer;
        });
        $password = $helper->ask($input, $output, $passwordQuestion);

        // Confirmar contraseña
        $confirmPasswordQuestion = new Question('Confirmar contraseña: ');
        $confirmPasswordQuestion->setHidden(true);
        $confirmPasswordQuestion->setHiddenFallback(false);
        $confirmPasswordQuestion->setValidator(function ($answer) use ($password) {
            if ($answer !== $password) {
                throw new \RuntimeException('Las contraseñas no coinciden');
            }
            return $answer;
        });
        $helper->ask($input, $output, $confirmPasswordQuestion);

        // Crear el administrador
        $admin = new Administrator();
        $admin->setEmail($email);
        $admin->setFirstName($firstName);
        $admin->setLastName($lastName);
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPermissionLevel($permissionLevel);
        $admin->setIsVerified(true); // Los admins están verificados por defecto
        
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $password);
        $admin->setPassword($hashedPassword);

        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success([
            '¡Administrador creado exitosamente! 🎉',
            "Email: {$email}",
            "Nombre: {$firstName} {$lastName}",
            "Nivel de permisos: {$permissionLevel}",
            "Roles: ROLE_ADMIN, ROLE_USER (heredado)"
        ]);

        return Command::SUCCESS;
    }
}

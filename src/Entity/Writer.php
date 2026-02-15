<?php

namespace App\Entity;

use App\Repository\WriterRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WriterRepository::class)]
class Writer extends User
{
    #[ORM\Column(length: 100, nullable: true)]
    #[Assert\Length(
        max: 100,
        maxMessage: 'La especialidad no puede tener más de {{ limit }} caracteres'
    )]
    private ?string $specialty = null;

    public function getSpecialty(): ?string
    {
        return $this->specialty;
    }

    public function setSpecialty(?string $specialty): static
    {
        $this->specialty = $specialty;

        return $this;
    }
}

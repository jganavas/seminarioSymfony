<?php

namespace App\Entity;

use App\Repository\AdministratorRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AdministratorRepository::class)]
class Administrator extends User
{
    #[ORM\Column]
    #[Assert\Range(
        min: 1,
        max: 5,
        notInRangeMessage: 'El nivel de permisos debe estar entre {{ min }} y {{ max }}'
    )]
    private int $permissionLevel = 1;

    public function getPermissionLevel(): int
    {
        return $this->permissionLevel;
    }

    public function setPermissionLevel(int $permissionLevel): static
    {
        $this->permissionLevel = $permissionLevel;

        return $this;
    }
}

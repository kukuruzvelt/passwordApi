<?php

namespace App\CompanySubdomain\SomeModule\Domain\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource]
#[ORM\Entity]
#[Post]
#[Patch]
#[Delete]
#[Get]
#[GetCollection(paginationClientItemsPerPage: true)]
class Password
{
    public function __construct(
        string $id,
        string $name,
        string $passwordValue
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->passwordValue = $passwordValue;
    }

    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $passwordValue;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPasswordValue(): string
    {
        return $this->passwordValue;
    }

    public function setPasswordValue(string $passwordValue): void
    {
        $this->passwordValue = $passwordValue;
    }
}

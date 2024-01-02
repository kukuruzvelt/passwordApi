<?php

namespace App\CompanySubdomain\SomeModule\Domain\Entity;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[Post]
#[Delete]
#[GetCollection]
class Password
{
    public function __construct(
        string $id,
        string $name,
        string $passwordValue,
        string $userId
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->passwordValue = $passwordValue;
        $this->userId = $userId;
    }

    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    private string $name;

    #[ORM\Column(type: 'string', length: 255)]
    private string $passwordValue;

    #[ORM\Column(type: 'string', length: 255)]
    private string $userId;

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

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->userId;
    }

    /**
     * @param string $userId
     */
    public function setUserId(string $userId): void
    {
        $this->userId = $userId;
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

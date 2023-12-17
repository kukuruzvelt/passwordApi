<?php

namespace App\CompanySubdomain\SomeModule\Domain\Entity;

use ApiPlatform\Metadata\Post;
use App\CompanySubdomain\SomeModule\Infrastructure\RegisterProcessor;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[Post(uriTemplate: 'register', normalizationContext: ['groups' => 'output'], input: UserInputDto::class, processor: RegisterProcessor::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public function __construct(
        string $id,
        string $email,
        string $password
    ) {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->roles = ['ROLE_USER'];
    }

    #[ORM\Id]
    #[ORM\Column]
    #[Assert\NotBlank]
    #[Groups('output')]
    private string $id;

    #[ORM\Column(type: 'string', length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    #[Groups('output')]
    private string $email;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    private string $password;

    #[ORM\Column(type: 'json')]
    #[Assert\NotBlank]
    #[Groups('output')]
    private array $roles;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}

<?php

namespace App\CompanySubdomain\SomeModule\Domain\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UserInputDto
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email;

    #[Assert\NotBlank]
    public string $password;
}

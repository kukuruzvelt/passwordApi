<?php

namespace App\CompanySubdomain\SomeModule\Infrastructure;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\CompanySubdomain\SomeModule\Domain\Entity\User;
use App\CompanySubdomain\SomeModule\Domain\Entity\UserInputDto;
use App\CompanySubdomain\SomeModule\Domain\UserRepository;
use App\Shared\Domain\ValueObject\Uuid;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterProcessor implements ProcessorInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher, private UserRepository $userRepository)
    {
    }

    /**
     * @param UserInputDto $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        $password = $data->password;
        $user = new User(Uuid::random()->value(), $data->email, $password);

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $password
        );
        $user->setPassword($hashedPassword);

        $this->userRepository->save($user);

        return $user;
    }
}

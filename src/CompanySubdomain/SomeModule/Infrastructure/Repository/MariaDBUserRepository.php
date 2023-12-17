<?php

declare(strict_types=1);

namespace App\CompanySubdomain\SomeModule\Infrastructure\Repository;

use App\CompanySubdomain\SomeModule\Domain\Entity\User;
use App\CompanySubdomain\SomeModule\Domain\UserRepository;
use App\CompanySubdomain\SomeModule\Infrastructure\Exceptions\DuplicateEmailError;
use App\CompanySubdomain\SomeModule\Infrastructure\Exceptions\UserNotFoundError;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;

final readonly class MariaDBUserRepository implements UserRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function save(User $user): void
    {
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (UniqueConstraintViolationException $e) {
            throw new DuplicateEmailError($user->getEmail());
        }
    }

    public function find(string $userID): User
    {
        $user = $this->entityManager->find(User::class, $userID);

        if (!$user) {
            throw new UserNotFoundError();
        }

        return $user;
    }
}

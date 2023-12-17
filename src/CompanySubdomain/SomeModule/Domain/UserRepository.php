<?php

declare(strict_types=1);

namespace App\CompanySubdomain\SomeModule\Domain;

use App\CompanySubdomain\SomeModule\Domain\Entity\User;

interface UserRepository
{
    public function save(User $user): void;

    public function find(string $userID): User;
}

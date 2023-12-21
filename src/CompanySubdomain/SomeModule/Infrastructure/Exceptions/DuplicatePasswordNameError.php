<?php

namespace App\CompanySubdomain\SomeModule\Infrastructure\Exceptions;

class DuplicatePasswordNameError extends \RuntimeException
{
    public function __construct(string $passwordName)
{
    parent::__construct($passwordName.' password name is already taken');
}
}
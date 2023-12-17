<?php

namespace App\CompanySubdomain\SomeModule\Infrastructure\Exceptions;

class InvalidPasswordError extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Old password is invalid');
    }
}

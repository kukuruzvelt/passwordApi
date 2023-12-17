<?php

namespace App\CompanySubdomain\SomeModule\Infrastructure\Exceptions;

class UserNotFoundError extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('User not found');
    }
}

<?php

namespace App\Exceptions\Domain\Company;

use Exception;

class DuplicateCompanyException extends Exception
{
    public function __construct(string $nit)
    {
        parent::__construct("Company with NIT {$nit} already exists");
    }
}

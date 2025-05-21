<?php

namespace App\Exceptions\Domain\Company;

use Exception;

class CompanyActiveException extends Exception
{
    public function __construct(string $nit)
    {
        parent::__construct("Company with NIT {$nit} is active");
    }
}

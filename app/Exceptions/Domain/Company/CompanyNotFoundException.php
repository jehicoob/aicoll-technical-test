<?php

namespace App\Exceptions\Domain\Company;

use Exception;

class CompanyNotFoundException extends Exception
{
    public function __construct(string $nit)
    {
        parent::__construct("Company with NIT {$nit} not found");
    }
}

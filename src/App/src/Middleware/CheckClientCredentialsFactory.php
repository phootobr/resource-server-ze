<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Container\ContainerInterface;

class CheckClientCredentialsFactory
{
    public function __invoke(ContainerInterface $container) : CheckClientCredentials
    {
        return new CheckClientCredentials();
    }
}

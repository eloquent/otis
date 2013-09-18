<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Exception;

use Eloquent\Otis\Hotp\Validator\Parameters\HotpParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Exception;
use PHPUnit_Framework_TestCase;

class UnsupportedMfaCombinationExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $configuration = new TotpConfiguration;
        $parameters = new HotpParameters('secret', 111, 'password');
        $exception = new UnsupportedMfaCombinationException($configuration, $parameters, $previous);

        $this->assertSame($configuration, $exception->configuration());
        $this->assertSame($parameters, $exception->parameters());
        $this->assertSame(
            "Unsupported multi-factor configuration and parameters combination " .
                "('Eloquent\\\\Otis\\\\Totp\\\\Configuration\\\\TotpConfiguration' and " .
                "'Eloquent\\\\Otis\\\\Hotp\\\\Validator\\\\Parameters\\\\HotpParameters').",
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}

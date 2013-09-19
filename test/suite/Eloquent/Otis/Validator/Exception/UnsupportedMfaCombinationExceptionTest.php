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

use Eloquent\Otis\Credentials\OtpCredentials;
use Eloquent\Otis\Hotp\Parameters\HotpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Exception;
use PHPUnit_Framework_TestCase;

class UnsupportedMfaCombinationExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $configuration = new TotpConfiguration;
        $shared = new HotpSharedParameters('secret', 111);
        $credentials = new OtpCredentials('password');
        $exception = new UnsupportedMfaCombinationException($configuration, $shared, $credentials, $previous);

        $this->assertSame($configuration, $exception->configuration());
        $this->assertSame($shared, $exception->shared());
        $this->assertSame($credentials, $exception->credentials());
        $this->assertSame(
            "Unsupported combination of multi-factor authentication configuration, shared parameters, and " .
                "credentials ('Eloquent\\\\Otis\\\\Totp\\\\Configuration\\\\TotpConfiguration', " .
                "'Eloquent\\\\Otis\\\\Hotp\\\\Parameters\\\\HotpSharedParameters' and " .
                "'Eloquent\\\\Otis\\\\Credentials\\\\OtpCredentials').",
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}

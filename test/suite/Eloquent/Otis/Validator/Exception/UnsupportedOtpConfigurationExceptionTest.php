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

use Eloquent\Otis\Configuration\TotpConfiguration;
use Exception;
use PHPUnit_Framework_TestCase;

class UnsupportedOtpConfigurationExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $configuration = new TotpConfiguration;
        $exception = new UnsupportedOtpConfigurationException($configuration, $previous);

        $this->assertSame($configuration, $exception->configuration());
        $this->assertSame(
            "OTP configuration of type 'Eloquent\\\\Otis\\\\Configuration\\\\TotpConfiguration' is not supported.",
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}

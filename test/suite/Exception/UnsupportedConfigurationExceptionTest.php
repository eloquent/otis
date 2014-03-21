<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Exception;

use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Exception;
use PHPUnit_Framework_TestCase;

class UnsupportedConfigurationExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $configuration = new TotpConfiguration;
        $exception = new UnsupportedConfigurationException($configuration, $previous);

        $this->assertSame($configuration, $exception->configuration());
        $this->assertSame(
            "Unsupported configuration of type " .
                "'Eloquent\\\\Otis\\\\Totp\\\\Configuration\\\\TotpConfiguration' supplied.",
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}

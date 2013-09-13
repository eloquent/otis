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

use Eloquent\Otis\Validator\Parameters\TotpParameters;
use Exception;
use PHPUnit_Framework_TestCase;

class OtpParametersTypeMismatchExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $parameters = new TotpParameters('secret', 'password');
        $exception = new OtpParametersTypeMismatchException('requiredType', $parameters, $previous);

        $this->assertSame('requiredType', $exception->requiredType());
        $this->assertSame($parameters, $exception->parameters());
        $this->assertSame(
            "Unexpected OTP parameters type 'Eloquent\\\\Otis\\\\Validator\\\\Parameters\\\\TotpParameters', " .
                "expected 'requiredType'.",
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}

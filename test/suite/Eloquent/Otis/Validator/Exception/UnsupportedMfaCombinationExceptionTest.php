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

use Exception;
use PHPUnit_Framework_TestCase;

class UnsupportedMfaCombinationExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $exception = new UnsupportedMfaCombinationException($previous);

        $this->assertSame(
            'Unsupported combination of multi-factor authentication, ' .
                'configuration, shared parameters, and credentials.',
            $exception->getMessage()
        );
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}

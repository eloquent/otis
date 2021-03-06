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

use Exception;
use PHPUnit_Framework_TestCase;

class InvalidPasswordLengthExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $exception = new InvalidPasswordLengthException(111, $previous);

        $this->assertSame(111, $exception->digits());
        $this->assertSame('Invalid password length (111).', $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}

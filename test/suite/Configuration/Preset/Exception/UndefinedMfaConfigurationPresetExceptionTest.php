<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration\Preset\Exception;

use Exception;
use PHPUnit_Framework_TestCase;

class UndefinedMfaConfigurationPresetExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testException()
    {
        $previous = new Exception;
        $exception = new UndefinedMfaConfigurationPresetException('key', $previous);

        $this->assertSame('key', $exception->key());
        $this->assertSame("Undefined multi-factor authentication preset 'key'.", $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }
}

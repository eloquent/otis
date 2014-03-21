<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp;

use Eloquent\Otis\Validator\TimeBasedOtpValidator;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Motp\MotpDriver
 * @covers \Eloquent\Otis\Driver\AbstractMfaDriver
 */
class MotpDriverTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->validator = new TimeBasedOtpValidator(
            new Value\MotpValueGenerator
        );
        $this->sharedParametersGenerator = new Parameters\Generator\MotpSharedParametersGenerator;
        $this->driver = new MotpDriver(
            $this->validator,
            $this->sharedParametersGenerator
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->validator, $this->driver->validator());
        $this->assertSame($this->sharedParametersGenerator, $this->driver->sharedParametersGenerator());
        $this->assertNull($this->driver->initializationUriFactory());
    }

    public function testConstructorDefaults()
    {
        $this->driver = new MotpDriver;

        $this->assertEquals($this->validator, $this->driver->validator());
        $this->assertEquals($this->sharedParametersGenerator, $this->driver->sharedParametersGenerator());
    }
}

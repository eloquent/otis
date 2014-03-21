<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp;

use Eloquent\Otis\GoogleAuthenticator\Uri\Initialization\GoogleAuthenticatorTotpUriFactory;
use Eloquent\Otis\Parameters\Generator\TimeBasedOtpSharedParametersGenerator;
use Eloquent\Otis\Validator\TimeBasedOtpValidator;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Totp\TotpDriver
 * @covers \Eloquent\Otis\Driver\AbstractMfaDriver
 */
class TotpDriverTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->validator = new TimeBasedOtpValidator(
            new Value\TotpValueGenerator
        );
        $this->sharedParametersGenerator = new TimeBasedOtpSharedParametersGenerator;
        $this->initializationUriFactory = new GoogleAuthenticatorTotpUriFactory;
        $this->driver = new TotpDriver(
            $this->validator,
            $this->sharedParametersGenerator,
            $this->initializationUriFactory
        );
    }

    public function testConstructor()
    {
        $this->assertSame($this->validator, $this->driver->validator());
        $this->assertSame($this->sharedParametersGenerator, $this->driver->sharedParametersGenerator());
        $this->assertSame($this->initializationUriFactory, $this->driver->initializationUriFactory());
    }

    public function testConstructorDefaults()
    {
        $this->driver = new TotpDriver;

        $this->assertEquals($this->validator, $this->driver->validator());
        $this->assertEquals($this->sharedParametersGenerator, $this->driver->sharedParametersGenerator());
        $this->assertEquals($this->initializationUriFactory, $this->driver->initializationUriFactory());
    }
}

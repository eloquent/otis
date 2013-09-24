<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp;

use Eloquent\Otis\GoogleAuthenticator\Uri\Initialization\GoogleAuthenticatorHotpUriFactory;
use Eloquent\Otis\Parameters\Generator\CounterBasedOtpSharedParametersGenerator;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Hotp\HotpDriver
 * @covers \Eloquent\Otis\Driver\AbstractMfaDriver
 */
class HotpDriverTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->validator = new Validator\HotpValidator;
        $this->sharedParametersGenerator = new CounterBasedOtpSharedParametersGenerator;
        $this->initializationUriFactory = new GoogleAuthenticatorHotpUriFactory;
        $this->driver = new HotpDriver(
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
        $this->driver = new HotpDriver;

        $this->assertEquals($this->validator, $this->driver->validator());
        $this->assertEquals($this->sharedParametersGenerator, $this->driver->sharedParametersGenerator());
        $this->assertEquals($this->initializationUriFactory, $this->driver->initializationUriFactory());
    }
}

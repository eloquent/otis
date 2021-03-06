<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Configuration;

use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Totp\Configuration\TotpConfiguration
 * @covers \Eloquent\Otis\Configuration\AbstractTimeBasedOtpConfiguration
 * @covers \Eloquent\Otis\Configuration\AbstractOtpConfiguration
 */
class TotpConfigurationTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new TotpConfiguration(10, 222, 333, 444, 555, HotpHashAlgorithm::SHA512());
    }

    public function testConstructor()
    {
        $this->assertSame(10, $this->configuration->digits());
        $this->assertSame(222, $this->configuration->window());
        $this->assertSame(333, $this->configuration->futureWindows());
        $this->assertSame(444, $this->configuration->pastWindows());
        $this->assertSame(555, $this->configuration->secretLength());
        $this->assertSame(HotpHashAlgorithm::SHA512(), $this->configuration->algorithm());
    }

    public function testConstructorDefaults()
    {
        $this->configuration = new TotpConfiguration;

        $this->assertSame(6, $this->configuration->digits());
        $this->assertSame(30, $this->configuration->window());
        $this->assertSame(1, $this->configuration->futureWindows());
        $this->assertSame(1, $this->configuration->pastWindows());
        $this->assertSame(10, $this->configuration->secretLength());
        $this->assertSame(HotpHashAlgorithm::SHA1(), $this->configuration->algorithm());
    }

    public function testConstructorFailurePasswordLengthTooShort()
    {
        $this->setExpectedException('Eloquent\Otis\Exception\InvalidPasswordLengthException');
        new TotpConfiguration(5);
    }

    public function testConstructorFailurePasswordLengthTooLong()
    {
        $this->setExpectedException('Eloquent\Otis\Exception\InvalidPasswordLengthException');
        new TotpConfiguration(11);
    }
}

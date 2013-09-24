<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Configuration;

use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Hotp\Configuration\HotpConfiguration
 * @covers \Eloquent\Otis\Configuration\AbstractCounterBasedOtpConfiguration
 * @covers \Eloquent\Otis\Configuration\AbstractOtpConfiguration
 */
class HotpConfigurationTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new HotpConfiguration(10, 222, 333, 444, HotpHashAlgorithm::SHA512());
    }

    public function testConstructor()
    {
        $this->assertSame(10, $this->configuration->digits());
        $this->assertSame(222, $this->configuration->window());
        $this->assertSame(333, $this->configuration->initialCounter());
        $this->assertSame(444, $this->configuration->secretLength());
        $this->assertSame(HotpHashAlgorithm::SHA512(), $this->configuration->algorithm());
    }

    public function testConstructorDefaults()
    {
        $this->configuration = new HotpConfiguration;

        $this->assertSame(6, $this->configuration->digits());
        $this->assertSame(10, $this->configuration->window());
        $this->assertSame(1, $this->configuration->initialCounter());
        $this->assertSame(10, $this->configuration->secretLength());
        $this->assertSame(HotpHashAlgorithm::SHA1(), $this->configuration->algorithm());
    }

    public function testConstructorFailurePasswordLengthTooShort()
    {
        $this->setExpectedException('Eloquent\Otis\Exception\InvalidPasswordLengthException');
        new HotpConfiguration(5);
    }

    public function testConstructorFailurePasswordLengthTooLong()
    {
        $this->setExpectedException('Eloquent\Otis\Exception\InvalidPasswordLengthException');
        new HotpConfiguration(11);
    }

    public function testSetWindow()
    {
        $this->configuration->setWindow(555);

        $this->assertSame(555, $this->configuration->window());
    }
}

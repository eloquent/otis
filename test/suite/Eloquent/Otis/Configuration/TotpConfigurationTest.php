<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Configuration\TotpConfiguration
 * @covers \Eloquent\Otis\Configuration\AbstractOtpConfiguration
 */
class TotpConfigurationTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new TotpConfiguration(
            111,
            222,
            333,
            HashAlgorithm::SHA512()
        );
    }

    public function testConstructor()
    {
        $this->assertSame(111, $this->configuration->digits());
        $this->assertSame(222, $this->configuration->window());
        $this->assertSame(333, $this->configuration->secretLength());
        $this->assertSame(HashAlgorithm::SHA512(), $this->configuration->algorithm());
    }

    public function testConstructorDefaults()
    {
        $this->configuration = new TotpConfiguration;

        $this->assertSame(6, $this->configuration->digits());
        $this->assertSame(30, $this->configuration->window());
        $this->assertSame(10, $this->configuration->secretLength());
        $this->assertSame(HashAlgorithm::SHA1(), $this->configuration->algorithm());
    }
}

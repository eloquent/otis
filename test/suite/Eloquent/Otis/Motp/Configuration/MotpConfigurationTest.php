<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Configuration;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Motp\Configuration\MotpConfiguration
 * @covers \Eloquent\Otis\Configuration\AbstractTimeBasedOtpConfiguration
 * @covers \Eloquent\Otis\Configuration\AbstractOtpConfiguration
 */
class MotpConfigurationTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new MotpConfiguration(111, 222);
    }

    public function testConstructor()
    {
        $this->assertSame(111, $this->configuration->futureWindows());
        $this->assertSame(222, $this->configuration->pastWindows());
    }

    public function testConstructorDefaults()
    {
        $this->configuration = new MotpConfiguration;

        $this->assertSame(3, $this->configuration->futureWindows());
        $this->assertSame(3, $this->configuration->pastWindows());
    }
}

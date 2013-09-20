<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters
 * @covers \Eloquent\Otis\Parameters\AbstractOtpSharedParameters
 */
class CounterBasedOtpSharedParametersTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->parameters = new CounterBasedOtpSharedParameters('secret', 111);
    }

    public function testConstructor()
    {
        $this->assertSame('secret', $this->parameters->secret());
        $this->assertSame(111, $this->parameters->counter());
    }

    public function testConstructorDefaults()
    {
        $this->parameters = new CounterBasedOtpSharedParameters('secret');

        $this->assertSame(1, $this->parameters->counter());
    }
}

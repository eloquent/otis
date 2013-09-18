<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator\Parameters;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Motp\Validator\Parameters\MotpParameters
 * @covers \Eloquent\Otis\Validator\Parameters\AbstractOtpParameters
 */
class MotpParametersTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->parameters = new MotpParameters('secret', 111, 'password');
    }

    public function testConstructor()
    {
        $this->assertSame('secret', $this->parameters->secret());
        $this->assertSame('password', $this->parameters->password());
        $this->assertSame(111, $this->parameters->pin());
    }
}

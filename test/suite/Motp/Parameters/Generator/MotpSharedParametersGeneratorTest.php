<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Parameters\Generator;

use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

class MotpSharedParametersGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        $this->generator = new MotpSharedParametersGenerator($this->isolator);
    }

    public function testGenerateMotp()
    {
        $configuration = new MotpConfiguration;
        Phake::when($this->isolator)->mcrypt_create_iv(8)->thenReturn('1234567890');
        Phake::when($this->isolator)->time()->thenReturn(111);
        $actual = $this->generator->generate($configuration);

        $this->assertInstanceOf('Eloquent\Otis\Motp\Parameters\MotpSharedParameters', $actual);
        $this->assertSame('1234567890', $actual->secret());
        $this->assertSame(111, $actual->time());
    }
}

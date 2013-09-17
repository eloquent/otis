<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Generator;

use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

class MotpGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->isolator = Phake::mock(Isolator::className());
        $this->generator = new MotpGenerator($this->isolator);
    }

    public function generateData()
    {
        //                                secret                          pin     time        expected
        return array(
            'Known good value 1' => array('12345678',                     '1234', 0,          'd64c7d'),
            'Known good value 2' => array('12345678',                     '1234', 9,          'd64c7d'),
            'Known good value 3' => array('12345678',                     '1234', 1234,       '6bfed1'),
            'Known good value 4' => array('12345678',                     '1234', 1240,       'ee20ed'),
            'Known good value 5' => array(pack('H*', 'fd85e62d9beb4542'), '1234', 1379424174, '65e5e8'),
        );
    }

    /**
     * @dataProvider generateData
     */
    public function testGenerate($secret, $pin, $time, $motp)
    {
        $result = $this->generator->generate($secret, $pin, $time);

        $this->assertSame($motp, $result);
    }

    public function testGenerateCurrentTime()
    {
        Phake::when($this->isolator)->time()->thenReturn(1234);

        $this->assertSame('6bfed1', $this->generator->generate('12345678', '1234', null));
    }
}

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

use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Motp\Parameters\MotpSharedParameters;
use PHPUnit_Framework_TestCase;

class MotpGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new MotpGenerator;
    }

    public function generateMotpData()
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
     * @dataProvider generateMotpData
     */
    public function testGenerateMotp($secret, $pin, $time, $motp)
    {
        $result = $this->generator->generateMotp(new MotpConfiguration, new MotpSharedParameters($secret, $pin, $time));

        $this->assertSame($motp, $result);
    }
}

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

use PHPUnit_Framework_TestCase;

class HotpGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new HotpGenerator;
    }

    public function generateData()
    {
        return array(
            'RFC 4226 test secret count 0' => array('12345678901234567890', 0, 1284755224, '755224'),
            'RFC 4226 test secret count 1' => array('12345678901234567890', 1, 1094287082, '287082'),
            'RFC 4226 test secret count 2' => array('12345678901234567890', 2, 137359152,  '359152'),
            'RFC 4226 test secret count 3' => array('12345678901234567890', 3, 1726969429, '969429'),
            'RFC 4226 test secret count 4' => array('12345678901234567890', 4, 1640338314, '338314'),
            'RFC 4226 test secret count 5' => array('12345678901234567890', 5, 868254676,  '254676'),
            'RFC 4226 test secret count 6' => array('12345678901234567890', 6, 1918287922, '287922'),
            'RFC 4226 test secret count 7' => array('12345678901234567890', 7, 82162583,   '162583'),
            'RFC 4226 test secret count 8' => array('12345678901234567890', 8, 673399871,  '399871'),
            'RFC 4226 test secret count 9' => array('12345678901234567890', 9, 645520489,  '520489'),
        );
    }

    /**
     * @dataProvider generateData
     */
    public function testGenerate($secret, $counter, $truncated, $hotp)
    {
        $result = $this->generator->generate($secret, $counter);

        $this->assertSame($truncated, $result->truncated());
        $this->assertSame($hotp, $result->string(6));
    }
}

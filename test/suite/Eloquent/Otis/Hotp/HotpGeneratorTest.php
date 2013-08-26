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
    public function testConstructor()
    {
        $this->generator = new HotpGenerator('sha256');

        $this->assertSame('sha256', $this->generator->algorithm());
    }

    public function testConstructorDefaults()
    {
        $this->generator = new HotpGenerator;

        $this->assertSame('sha1', $this->generator->algorithm());
    }

    public function generateData()
    {
        //                                          secret                  counter truncated   hotp      algorithm
        return array(
            'RFC 4226 test secret count 0' => array('12345678901234567890', 0,      1284755224, '755224', null),
            'RFC 4226 test secret count 1' => array('12345678901234567890', 1,      1094287082, '287082', null),
            'RFC 4226 test secret count 2' => array('12345678901234567890', 2,      137359152,  '359152', null),
            'RFC 4226 test secret count 3' => array('12345678901234567890', 3,      1726969429, '969429', null),
            'RFC 4226 test secret count 4' => array('12345678901234567890', 4,      1640338314, '338314', null),
            'RFC 4226 test secret count 5' => array('12345678901234567890', 5,      868254676,  '254676', null),
            'RFC 4226 test secret count 6' => array('12345678901234567890', 6,      1918287922, '287922', null),
            'RFC 4226 test secret count 7' => array('12345678901234567890', 7,      82162583,   '162583', null),
            'RFC 4226 test secret count 8' => array('12345678901234567890', 8,      673399871,  '399871', null),
            'RFC 4226 test secret count 9' => array('12345678901234567890', 9,      645520489,  '520489', null),

            'SHA-256 variant count 0'      => array('12345678901234567890', 0,      2074875740, '875740', 'sha256'),
            'SHA-256 variant count 1'      => array('12345678901234567890', 1,      1332247374, '247374', 'sha256'),
            'SHA-256 variant count 2'      => array('12345678901234567890', 2,      1766254785, '254785', 'sha256'),
            'SHA-256 variant count 3'      => array('12345678901234567890', 3,      667496144,  '496144', 'sha256'),
            'SHA-256 variant count 4'      => array('12345678901234567890', 4,      1625480556, '480556', 'sha256'),
            'SHA-256 variant count 5'      => array('12345678901234567890', 5,      89697997,   '697997', 'sha256'),
            'SHA-256 variant count 6'      => array('12345678901234567890', 6,      640191609,  '191609', 'sha256'),
            'SHA-256 variant count 7'      => array('12345678901234567890', 7,      1267579288, '579288', 'sha256'),
            'SHA-256 variant count 8'      => array('12345678901234567890', 8,      1883895912, '895912', 'sha256'),
            'SHA-256 variant count 9'      => array('12345678901234567890', 9,      223184989,  '184989', 'sha256'),

            'SHA-512 variant count 0'      => array('12345678901234567890', 0,      604125165,  '125165', 'sha512'),
            'SHA-512 variant count 1'      => array('12345678901234567890', 1,      369342147,  '342147', 'sha512'),
            'SHA-512 variant count 2'      => array('12345678901234567890', 2,      671730102,  '730102', 'sha512'),
            'SHA-512 variant count 3'      => array('12345678901234567890', 3,      573778726,  '778726', 'sha512'),
            'SHA-512 variant count 4'      => array('12345678901234567890', 4,      1581937510, '937510', 'sha512'),
            'SHA-512 variant count 5'      => array('12345678901234567890', 5,      1516848329, '848329', 'sha512'),
            'SHA-512 variant count 6'      => array('12345678901234567890', 6,      836266680,  '266680', 'sha512'),
            'SHA-512 variant count 7'      => array('12345678901234567890', 7,      22588359,   '588359', 'sha512'),
            'SHA-512 variant count 8'      => array('12345678901234567890', 8,      245039399,  '039399', 'sha512'),
            'SHA-512 variant count 9'      => array('12345678901234567890', 9,      1033643409, '643409', 'sha512'),
        );
    }

    /**
     * @dataProvider generateData
     */
    public function testGenerate($secret, $counter, $truncated, $hotp, $algorithm)
    {
        $this->generator = new HotpGenerator($algorithm);
        $result = $this->generator->generate($secret, $counter);

        $this->assertSame($truncated, $result->truncated());
        $this->assertSame($hotp, $result->string(6));
    }
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Generator;

use Eloquent\Otis\Hotp\Generator\HotpGenerator;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

class TotpGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->hotpGenerator = new HotpGenerator;
        $this->isolator = Phake::mock(Isolator::className());
        $this->generator = new TotpGenerator($this->hotpGenerator, $this->isolator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->hotpGenerator, $this->generator->generator());
    }

    public function testConstructorDefaults()
    {
        $this->generator = new TotpGenerator;

        $this->assertEquals(new HotpGenerator, $this->generator->generator());
    }

    public function generateData()
    {
        //                                      secret                                                              window time         expected    algorithm
        return array(
            'RFC 6238 test vector 1'   => array('12345678901234567890',                                             null,  59,          '94287082', null),
            'RFC 6238 test vector 2'   => array('12345678901234567890123456789012',                                 null,  59,          '46119246', 'SHA256'),
            'RFC 6238 test vector 3'   => array('1234567890123456789012345678901234567890123456789012345678901234', null,  59,          '90693936', 'SHA512'),
            'RFC 6238 test vector 4'   => array('12345678901234567890',                                             null,  1111111109,  '07081804', null),
            'RFC 6238 test vector 5'   => array('12345678901234567890123456789012',                                 null,  1111111109,  '68084774', 'SHA256'),
            'RFC 6238 test vector 6'   => array('1234567890123456789012345678901234567890123456789012345678901234', null,  1111111109,  '25091201', 'SHA512'),
            'RFC 6238 test vector 7'   => array('12345678901234567890',                                             null,  1111111111,  '14050471', null),
            'RFC 6238 test vector 8'   => array('12345678901234567890123456789012',                                 null,  1111111111,  '67062674', 'SHA256'),
            'RFC 6238 test vector 9'   => array('1234567890123456789012345678901234567890123456789012345678901234', null,  1111111111,  '99943326', 'SHA512'),
            'RFC 6238 test vector 10'  => array('12345678901234567890',                                             null,  1234567890,  '89005924', null),
            'RFC 6238 test vector 11'  => array('12345678901234567890123456789012',                                 null,  1234567890,  '91819424', 'SHA256'),
            'RFC 6238 test vector 12'  => array('1234567890123456789012345678901234567890123456789012345678901234', null,  1234567890,  '93441116', 'SHA512'),
            'RFC 6238 test vector 13'  => array('12345678901234567890',                                             null,  2000000000,  '69279037', null),
            'RFC 6238 test vector 14'  => array('12345678901234567890123456789012',                                 null,  2000000000,  '90698825', 'SHA256'),
            'RFC 6238 test vector 15'  => array('1234567890123456789012345678901234567890123456789012345678901234', null,  2000000000,  '38618901', 'SHA512'),
            'RFC 6238 test vector 16'  => array('12345678901234567890',                                             null,  20000000000, '65353130', null),
            'RFC 6238 test vector 17'  => array('12345678901234567890123456789012',                                 null,  20000000000, '77737706', 'SHA256'),
            'RFC 6238 test vector 18'  => array('1234567890123456789012345678901234567890123456789012345678901234', null,  20000000000, '47863826', 'SHA512'),

            'Alternate window SHA-1'   => array('12345678901234567890',                                             60,    1111111111,  '19360094', null),
            'Alternate window SHA-256' => array('12345678901234567890123456789012',                                 60,    1111111111,  '40857319', 'SHA256'),
            'Alternate window SHA-512' => array('1234567890123456789012345678901234567890123456789012345678901234', 60,    1111111111,  '37023009', 'SHA512'),
        );
    }

    /**
     * @dataProvider generateData
     */
    public function testGenerate($secret, $window, $time, $totp, $algorithm)
    {
        $result = $this->generator->generate(
            $secret,
            $window,
            $time,
            HotpHashAlgorithm::memberByValueWithDefault($algorithm)
        );

        $this->assertSame($totp, $result->string(8));
    }

    public function testGenerateCurrentTime()
    {
        Phake::when($this->isolator)->time()->thenReturn(1111111111);

        $this->assertSame('14050471', $this->generator->generate('12345678901234567890', null)->string(8));
    }
}

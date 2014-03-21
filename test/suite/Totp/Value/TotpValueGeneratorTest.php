<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Value;

use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Hotp\Value\HotpValueGenerator;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use PHPUnit_Framework_TestCase;

class TotpValueGeneratorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->hotpGenerator = new HotpValueGenerator;
        $this->generator = new TotpValueGenerator($this->hotpGenerator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->hotpGenerator, $this->generator->generator());
    }

    public function testConstructorDefaults()
    {
        $this->generator = new TotpValueGenerator;

        $this->assertEquals(new HotpValueGenerator, $this->generator->generator());
    }

    public function generateTotpData()
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
     * @dataProvider generateTotpData
     */
    public function testGenerateTotp($secret, $window, $time, $totp, $algorithm)
    {
        $result = $this->generator->generate(
            new TotpConfiguration(
                8,
                $window,
                null,
                null,
                strlen($secret),
                HotpHashAlgorithm::memberByValueWithDefault($algorithm)
            ),
            new TimeBasedOtpSharedParameters($secret, $time)
        );

        $this->assertSame($totp, $result->string(8));
    }
}

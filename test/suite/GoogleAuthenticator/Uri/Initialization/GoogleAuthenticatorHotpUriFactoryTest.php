<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\GoogleAuthenticator\Uri\Initialization;

use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\GoogleAuthenticator\Uri\Initialization\GoogleAuthenticatorHotpUriFactory
 * @covers \Eloquent\Otis\GoogleAuthenticator\Uri\Initialization\AbstractGoogleAuthenticatorUriFactory
 */
class GoogleAuthenticatorHotpUriFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new GoogleAuthenticatorHotpUriFactory;
    }

    public function createData()
    {
        //                          secret                  label                    issuer    counter digits algorithm expected
        return array(
            'All defaults' => array('12345678901234567890', 'test.ease@example.org', null,     null,   null,  null,     'otpauth://hotp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'),
            'All options'  => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', 'otpauth://hotp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&counter=111&digits=10&algorithm=SHA256&issuer=Skynet'),
        );
    }

    /**
     * @dataProvider createData
     */
    public function testCreate($secret, $label, $issuer, $counter, $digits, $algorithm, $expected)
    {
        $configuration = new HotpConfiguration(
            $digits,
            null,
            null,
            null,
            HotpHashAlgorithm::memberByValueWithDefault($algorithm)
        );
        $shared = new CounterBasedOtpSharedParameters($secret, $counter);

        $this->assertSame($expected, $this->factory->create($configuration, $shared, $label, $issuer));
    }

    public function createHotpData()
    {
        //                               secret                  label                    issuer    counter digits algorithm issuerInLabel expected
        return array(
            'All defaults'      => array('12345678901234567890', 'test.ease@example.org', null,     null,   null,  null,     null,         'otpauth://hotp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'),
            'All options'       => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', true,         'otpauth://hotp/Skynet:test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&counter=111&digits=10&algorithm=SHA256&issuer=Skynet'),
            'No legacy issuer'  => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', false,        'otpauth://hotp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&counter=111&digits=10&algorithm=SHA256&issuer=Skynet'),
        );
    }

    /**
     * @dataProvider createHotpData
     */
    public function testCreateHotp(
        $secret,
        $label,
        $issuer,
        $counter,
        $digits,
        $algorithm,
        $issuerInLabel,
        $expected
    ) {
        $configuration = new HotpConfiguration(
            $digits,
            null,
            null,
            null,
            HotpHashAlgorithm::memberByValueWithDefault($algorithm)
        );
        $shared = new CounterBasedOtpSharedParameters($secret, $counter);

        $this->assertSame(
            $expected,
            $this->factory->createHotp($configuration, $shared, $label, $issuer, $issuerInLabel)
        );
    }
}

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

use Eloquent\Endec\Base32\Base32;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\GoogleAuthenticator\Uri\Initialization\GoogleAuthenticatorTotpUriFactory
 * @covers \Eloquent\Otis\GoogleAuthenticator\Uri\Initialization\AbstractGoogleAuthenticatorUriFactory
 */
class GoogleAuthenticatorTotpUriFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->base32Encoder = new Base32;
        $this->factory = new GoogleAuthenticatorTotpUriFactory($this->base32Encoder);
    }

    public function testConstructor()
    {
        $this->assertSame($this->base32Encoder, $this->factory->base32Encoder());
    }

    public function testConstructorDefaults()
    {
        $this->factory = new GoogleAuthenticatorHotpUriFactory;

        $this->assertSame(Base32::instance(), $this->factory->base32Encoder());
    }

    public function createData()
    {
        //                          secret                  label                    issuer    window  digits algorithm expected
        return array(
            'All defaults' => array('12345678901234567890', 'test.ease@example.org', null,     null,   null,  null,     'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'),
            'All options'  => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', 'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&period=111&digits=10&algorithm=SHA256&issuer=Skynet'),
        );
    }

    /**
     * @dataProvider createData
     */
    public function testCreate($secret, $label, $issuer, $window, $digits, $algorithm, $expected)
    {
        $configuration = new TotpConfiguration(
            $digits,
            $window,
            null,
            null,
            null,
            HotpHashAlgorithm::memberByValueWithDefault($algorithm)
        );
        $shared = new TimeBasedOtpSharedParameters($secret, 111);

        $this->assertSame($expected, $this->factory->create($configuration, $shared, $label, $issuer));
    }

    public function createTotpData()
    {
        //                               secret                  label                    issuer    window  digits algorithm issuerInLabel expected
        return array(
            'All defaults'      => array('12345678901234567890', 'test.ease@example.org', null,     null,   null,  null,     null,         'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'),
            'All options'       => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', true,         'otpauth://totp/Skynet:test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&period=111&digits=10&algorithm=SHA256&issuer=Skynet'),
            'No legacy issuer'  => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', false,        'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&period=111&digits=10&algorithm=SHA256&issuer=Skynet'),
        );
    }

    /**
     * @dataProvider createTotpData
     */
    public function testCreateTotp(
        $secret,
        $label,
        $issuer,
        $window,
        $digits,
        $algorithm,
        $issuerInLabel,
        $expected
    ) {
        $configuration = new TotpConfiguration(
            $digits,
            $window,
            null,
            null,
            null,
            HotpHashAlgorithm::memberByValueWithDefault($algorithm)
        );
        $shared = new TimeBasedOtpSharedParameters($secret, 111);

        $this->assertSame(
            $expected,
            $this->factory->createTotp($configuration, $shared, $label, $issuer, $issuerInLabel)
        );
    }
}

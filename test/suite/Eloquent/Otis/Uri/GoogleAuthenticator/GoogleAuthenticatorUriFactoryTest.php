<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Uri\GoogleAuthenticator;

use Eloquent\Otis\Configuration\HashAlgorithm;
use Eloquent\Otis\Configuration\HotpConfiguration;
use Eloquent\Otis\Configuration\TotpConfiguration;
use PHPUnit_Framework_TestCase;

class GoogleAuthenticatorUriFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->factory = new GoogleAuthenticatorUriFactory;
    }

    public function createHotpUriData()
    {
        //                               secret                  label                    issuer    counter digits algorithm issuerInLabel expected
        return array(
            'All defaults'      => array('12345678901234567890', 'test.ease@example.org', null,     null,   null,  null,     null,         'otpauth://hotp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'),
            'All options'       => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', true,         'otpauth://hotp/Skynet:test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&counter=111&digits=10&algorithm=SHA256&issuer=Skynet'),
            'No legacy issuer'  => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', false,        'otpauth://hotp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&counter=111&digits=10&algorithm=SHA256&issuer=Skynet'),
        );
    }

    /**
     * @dataProvider createHotpUriData
     */
    public function testCreateHotpUri(
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
            HashAlgorithm::memberByValueWithDefault($algorithm)
        );

        $this->assertSame($expected, $this->factory->createHotpUri($configuration, $secret, $label, $counter, $issuer, $issuerInLabel));
    }

    public function createTotpUriData()
    {
        //                               secret                  label                    issuer    window  digits algorithm issuerInLabel expected
        return array(
            'All defaults'      => array('12345678901234567890', 'test.ease@example.org', null,     null,   null,  null,     null,         'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'),
            'All options'       => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', true,         'otpauth://totp/Skynet:test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&period=111&digits=10&algorithm=SHA256&issuer=Skynet'),
            'No legacy issuer'  => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', false,        'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&period=111&digits=10&algorithm=SHA256&issuer=Skynet'),
        );
    }

    /**
     * @dataProvider createTotpUriData
     */
    public function testCreateTotpUri(
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
            HashAlgorithm::memberByValueWithDefault($algorithm)
        );

        $this->assertSame(
            $expected,
            $this->factory->createTotpUri($configuration, $secret, $label, $issuer, $issuerInLabel)
        );
    }
}
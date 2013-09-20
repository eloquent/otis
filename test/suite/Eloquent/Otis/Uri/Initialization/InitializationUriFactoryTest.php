<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Uri\Initialization;

use Eloquent\Otis\GoogleAuthenticator\Uri\GoogleAuthenticatorUriFactory;
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Uri\Initialization\InitializationUriFactory
 * @covers \Eloquent\Otis\GoogleAuthenticator\Uri\GoogleAuthenticatorUriFactory
 */
class InitializationUriFactoryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->googleAuthenticatorFactory = new GoogleAuthenticatorUriFactory;
        $this->factories = array(
            $this->googleAuthenticatorFactory,
        );
        $this->factory = new InitializationUriFactory($this->factories);
    }

    public function testConstructor()
    {
        $this->assertSame($this->factories, $this->factory->factories());
    }

    public function testConstructorDefaults()
    {
        $this->factory = new InitializationUriFactory;

        $this->assertEquals($this->factories, $this->factory->factories());
    }

    public function supportsData()
    {
        //                         configuration          shared                                              expected
        return array(
            'Supported'   => array(new HotpConfiguration, new CounterBasedOtpSharedParameters('secret', 111), true),
            'Unsupported' => array(new MotpConfiguration, new TimeBasedOtpSharedParameters('secret', 111),    false),
        );
    }

    /**
     * @dataProvider supportsData
     */
    public function testSupports($configuration, $shared, $expected)
    {
        $this->assertSame($expected, $this->factory->supports($configuration, $shared));
    }

    public function createHotpData()
    {
        //                          secret                  label                    issuer    counter digits algorithm expected
        return array(
            'All defaults' => array('12345678901234567890', 'test.ease@example.org', null,     null,   null,  null,     'otpauth://hotp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'),
            'All options'  => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', 'otpauth://hotp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&counter=111&digits=10&algorithm=SHA256&issuer=Skynet'),
        );
    }

    /**
     * @dataProvider createHotpData
     */
    public function testCreateHotp($secret, $label, $issuer, $counter, $digits, $algorithm, $expected)
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

    public function createTotpData()
    {
        //                          secret                  label                    issuer    window  digits algorithm expected
        return array(
            'All defaults' => array('12345678901234567890', 'test.ease@example.org', null,     null,   null,  null,     'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'),
            'All options'  => array('12345678901234567890', 'test.ease@example.org', 'Skynet', 111,    10,    'SHA256', 'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ&period=111&digits=10&algorithm=SHA256&issuer=Skynet'),
        );
    }

    /**
     * @dataProvider createTotpData
     */
    public function testCreateTotp($secret, $label, $issuer, $window, $digits, $algorithm, $expected)
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

    public function testCreateFailureUnsupported()
    {
        $configuration = new HotpConfiguration;
        $shared = new TimeBasedOtpSharedParameters('secret', 111);

        $this->setExpectedException('Eloquent\Otis\Exception\UnsupportedArgumentsException');
        $this->factory->create($configuration, $shared, 'label');
    }
}

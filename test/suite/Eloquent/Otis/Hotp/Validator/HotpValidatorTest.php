<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Validator;

use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Credentials\HotpCredentials;
use Eloquent\Otis\Hotp\Generator\HotpGenerator;
use Eloquent\Otis\Hotp\Parameters\HotpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Credentials\TotpCredentials;
use Eloquent\Otis\Totp\Parameters\TotpSharedParameters;
use PHPUnit_Framework_TestCase;

class HotpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new HotpGenerator;
        $this->validator = new HotpValidator($this->generator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->generator, $this->validator->generator());
    }

    public function testConstructorDefaults()
    {
        $this->validator = new HotpValidator;

        $this->assertEquals($this->generator, $this->validator->generator());
    }

    public function supportsData()
    {
        //                                           configuration          shared                                   credentials                      expected
        return array(
            'Valid combination'             => array(new HotpConfiguration, new HotpSharedParameters('secret', 123), new HotpCredentials('password'), true),
            'Unsupported credentials'       => array(new HotpConfiguration, new HotpSharedParameters('secret', 123), new TotpCredentials('password'), false),
            'Unsupported shared parameters' => array(new HotpConfiguration, new TotpSharedParameters('secret'),      new HotpCredentials('password'), false),
            'Unsupported configuration'     => array(new TotpConfiguration, new HotpSharedParameters('secret', 123), new HotpCredentials('password'), false),
        );
    }

    /**
     * @dataProvider supportsData
     */
    public function testSupports($configuration, $shared, $credentials, $expected)
    {
        $this->assertSame($expected, $this->validator->supports($configuration, $shared, $credentials));
    }

    public function testValidateFailureUnsupported()
    {
        $configuration = new TotpConfiguration;
        $shared = new TotpSharedParameters('secret');
        $credentials = new TotpCredentials('password');

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validate($configuration, $shared, $credentials);
    }

    public function validateHotpSequenceData()
    {
        //                                  passwords                  secret                  currentCounter digits window result                        newCounter
        return array(
            'No window, valid'     => array(array('969429', '338314'), '12345678901234567890', 3,             null,  null,  'valid',                      5),
            'With window, valid'   => array(array('399871', '520489'), '12345678901234567890', 0,             null,  8,     'valid',                      10),

            'No window, invalid'   => array(array('359152', '969429'), '12345678901234567890', 3,             null,  0,     'invalid-credentials',        null),
            'With window, invalid' => array(array('755224', '359152'), '12345678901234567890', 0,             null,  100,   'invalid-credentials',        null),
            'Length mismatch'      => array(array('969429', '338314'), '12345678901234567890', 3,             8,     null,  'credential-length-mismatch', null),
            'No credentials'       => array(array(),                   '12345678901234567890', 0,             null,  100,   'empty-credential-sequence',  null),
        );
    }

    /**
     * @dataProvider validateHotpSequenceData
     */
    public function testValidateHotpSequence($passwords, $secret, $currentCounter, $digits, $window, $result, $newCounter)
    {
        $configuration = new HotpConfiguration($digits, $window);
        $shared = new HotpSharedParameters($secret, $currentCounter);
        $credentialSequence = array();
        foreach ($passwords as $password) {
            $credentialSequence[] = new HotpCredentials($password);
        }
        $actual = $this->validator->validateHotpSequence($configuration, $shared, $credentialSequence);

        $this->assertInstanceOf(__NAMESPACE__ . '\Result\HotpValidationResult', $actual);
        $this->assertSame($result, $actual->type());
        $this->assertSame($newCounter, $actual->counter());
    }
}

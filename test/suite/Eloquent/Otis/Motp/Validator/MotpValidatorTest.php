<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator;

use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Motp\Generator\MotpGenerator;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Validator\Parameters\TotpParameters;
use Icecave\Isolator\Isolator;
use PHPUnit_Framework_TestCase;
use Phake;

class MotpValidatorTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->generator = new MotpGenerator;
        $this->isolator = Phake::mock(Isolator::className());
        $this->validator = new MotpValidator($this->generator, $this->isolator);
    }

    public function testConstructor()
    {
        $this->assertSame($this->generator, $this->validator->generator());
    }

    public function testConstructorDefaults()
    {
        $this->validator = new MotpValidator;

        $this->assertEquals($this->generator, $this->validator->generator());
    }

    public function supportsData()
    {
        //                                       configuration          parameters                                                expected
        return array(
            'Valid combination'         => array(new MotpConfiguration, new Parameters\MotpParameters('secret', 111, 'password'), true),
            'Unsupported parameters'    => array(new MotpConfiguration, new TotpParameters('secret', 'password'),                 false),
            'Unsupported configuration' => array(new TotpConfiguration, new Parameters\MotpParameters('secret', 111, 'password'), false),
        );
    }

    /**
     * @dataProvider supportsData
     */
    public function testSupports($configuration, $parameters, $expected)
    {
        $this->assertSame($expected, $this->validator->supports($configuration, $parameters));
    }

    public function testValidateFailureUnsupported()
    {
        $configuration = new TotpConfiguration;
        $parameters = new TotpParameters('secret', 'password');

        $this->setExpectedException('Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException');
        $this->validator->validate($configuration, $parameters);
    }
}

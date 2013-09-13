<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator;

use Eloquent\Otis\Configuration\HashAlgorithm;
use Eloquent\Otis\Configuration\HotpConfiguration;
use Eloquent\Otis\Generator\HotpGenerator;
use PHPUnit_Framework_TestCase;
use Phake;

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

    public function validateSequenceData()
    {
        //                                  passwords                  secret                  currentCounter digits window result                      newCounter
        return array(
            'No window, valid'     => array(array('969429', '338314'), '12345678901234567890', 3,             null,  null,  'valid',                    5),
            'With window, valid'   => array(array('399871', '520489'), '12345678901234567890', 0,             null,  8,     'valid',                    10),

            'No window, invalid'   => array(array('359152', '969429'), '12345678901234567890', 3,             null,  0,     'invalid-password',         null),
            'With window, invalid' => array(array('755224', '359152'), '12345678901234567890', 0,             null,  100,   'invalid-password',         null),
            'Length mismatch'      => array(array('969429', '338314'), '12345678901234567890', 3,             8,     null,  'password-length-mismatch', null),
            'No passwords'         => array(array(),                   '12345678901234567890', 0,             null,  100,   'empty-password-sequence',  null),
        );
    }

    /**
     * @dataProvider validateSequenceData
     */
    public function testValidateSequence($passwords, $secret, $currentCounter, $digits, $window, $result, $newCounter)
    {
        $configuration = new HotpConfiguration($digits, $window);
        $actual = $this->validator->validateSequence($configuration, $secret, $passwords, $currentCounter);

        $this->assertInstanceOf(__NAMESPACE__ . '\Result\HotpValidationResult', $actual);
        $this->assertSame($result, $actual->type()->value());
        $this->assertSame($newCounter, $actual->counter());
    }
}

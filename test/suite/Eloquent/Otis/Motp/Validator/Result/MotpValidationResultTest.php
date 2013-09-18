<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator\Result;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Motp\Validator\Result\MotpValidationResult
 * @covers \Eloquent\Otis\Validator\Result\AbstractTimeBasedOtpValidationResult
 * @covers \Eloquent\Otis\Validator\Result\AbstractMfaValidationResult
 */
class MotpValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSuccessResult()
    {
        $result = new MotpValidationResult(MotpValidationResult::VALID, 111);

        $this->assertSame(MotpValidationResult::VALID, $result->type());
        $this->assertTrue($result->isSuccessful());
        $this->assertSame(111, $result->drift());
    }

    public function testConstructorUnsuccessfulResult()
    {
        $result = new MotpValidationResult(MotpValidationResult::INVALID_PASSWORD);

        $this->assertSame(MotpValidationResult::INVALID_PASSWORD, $result->type());
        $this->assertFalse($result->isSuccessful());
        $this->assertNull($result->drift());
    }

    public function testConstructorFailureSuccessButNoDrift()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new MotpValidationResult(MotpValidationResult::VALID);
    }

    public function testConstructorFailureUnsuccessfulWithDrift()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new MotpValidationResult(MotpValidationResult::INVALID_PASSWORD, 111);
    }
}

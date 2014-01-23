<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Result;

use PHPUnit_Framework_TestCase;

class TimeBasedOtpValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSuccessResult()
    {
        $result = new TimeBasedOtpValidationResult(TimeBasedOtpValidationResult::VALID, 111);

        $this->assertSame(TimeBasedOtpValidationResult::VALID, $result->type());
        $this->assertTrue($result->isSuccessful());
        $this->assertSame(111, $result->drift());
    }

    public function testConstructorUnsuccessfulResult()
    {
        $result = new TimeBasedOtpValidationResult(TimeBasedOtpValidationResult::INVALID_CREDENTIALS);

        $this->assertSame(TimeBasedOtpValidationResult::INVALID_CREDENTIALS, $result->type());
        $this->assertFalse($result->isSuccessful());
        $this->assertNull($result->drift());
    }

    public function testConstructorFailureSuccessButNoDrift()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new TimeBasedOtpValidationResult(TimeBasedOtpValidationResult::VALID);
    }

    public function testConstructorFailureUnsuccessfulWithDrift()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new TimeBasedOtpValidationResult(TimeBasedOtpValidationResult::INVALID_CREDENTIALS, 111);
    }
}

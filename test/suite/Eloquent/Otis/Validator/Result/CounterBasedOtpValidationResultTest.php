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

class CounterBasedOtpValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSuccessResult()
    {
        $result = new CounterBasedOtpValidationResult(CounterBasedOtpValidationResult::VALID, 111);

        $this->assertSame(CounterBasedOtpValidationResult::VALID, $result->type());
        $this->assertTrue($result->isSuccessful());
        $this->assertSame(111, $result->counter());
    }

    public function testConstructorUnsuccessfulResult()
    {
        $result = new CounterBasedOtpValidationResult(CounterBasedOtpValidationResult::INVALID_CREDENTIALS);

        $this->assertSame(CounterBasedOtpValidationResult::INVALID_CREDENTIALS, $result->type());
        $this->assertFalse($result->isSuccessful());
        $this->assertNull($result->counter());
    }

    public function testConstructorFailureSuccessButNoCounter()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new CounterBasedOtpValidationResult(CounterBasedOtpValidationResult::VALID);
    }

    public function testConstructorFailureUnsuccessfulWithCounter()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new CounterBasedOtpValidationResult(CounterBasedOtpValidationResult::INVALID_CREDENTIALS, 111);
    }
}

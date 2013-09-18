<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Validator\Result;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Hotp\Validator\Result\HotpValidationResult
 * @covers \Eloquent\Otis\Validator\Result\AbstractCounterBasedOtpValidationResult
 * @covers \Eloquent\Otis\Validator\Result\AbstractMfaValidationResult
 */
class HotpValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSuccessResult()
    {
        $result = new HotpValidationResult(HotpValidationResult::VALID, 111);

        $this->assertSame(HotpValidationResult::VALID, $result->type());
        $this->assertTrue($result->isSuccessful());
        $this->assertSame(111, $result->counter());
    }

    public function testConstructorUnsuccessfulResult()
    {
        $result = new HotpValidationResult(HotpValidationResult::INVALID_PASSWORD);

        $this->assertSame(HotpValidationResult::INVALID_PASSWORD, $result->type());
        $this->assertFalse($result->isSuccessful());
        $this->assertNull($result->counter());
    }

    public function testConstructorFailureSuccessButNoCounter()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new HotpValidationResult(HotpValidationResult::VALID);
    }

    public function testConstructorFailureUnsuccessfulWithCounter()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new HotpValidationResult(HotpValidationResult::INVALID_PASSWORD, 111);
    }
}

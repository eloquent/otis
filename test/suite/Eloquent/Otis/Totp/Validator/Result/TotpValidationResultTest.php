<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Validator\Result;

use PHPUnit_Framework_TestCase;

/**
 * @covers \Eloquent\Otis\Totp\Validator\Result\TotpValidationResult
 * @covers \Eloquent\Otis\Validator\Result\AbstractTimeBasedOtpValidationResult
 * @covers \Eloquent\Otis\Validator\Result\AbstractMfaValidationResult
 */
class TotpValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSuccessResult()
    {
        $result = new TotpValidationResult(TotpValidationResult::VALID, 111);

        $this->assertSame(TotpValidationResult::VALID, $result->type());
        $this->assertTrue($result->isSuccessful());
        $this->assertSame(111, $result->drift());
    }

    public function testConstructorUnsuccessfulResult()
    {
        $result = new TotpValidationResult(TotpValidationResult::INVALID_CREDENTIALS);

        $this->assertSame(TotpValidationResult::INVALID_CREDENTIALS, $result->type());
        $this->assertFalse($result->isSuccessful());
        $this->assertNull($result->drift());
    }

    public function testConstructorFailureSuccessButNoDrift()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new TotpValidationResult(TotpValidationResult::VALID);
    }

    public function testConstructorFailureUnsuccessfulWithDrift()
    {
        $this->setExpectedException('Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException');
        new TotpValidationResult(TotpValidationResult::INVALID_CREDENTIALS, 111);
    }
}

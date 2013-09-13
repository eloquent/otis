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

/**
 * @covers \Eloquent\Otis\Validator\Result\TotpValidationResult
 * @covers \Eloquent\Otis\Validator\Result\AbstractOtpValidationResult
 */
class TotpValidationResultTest extends PHPUnit_Framework_TestCase
{
    public function testConstructorSuccessResult()
    {
        $result = new TotpValidationResult(ValidationResultType::VALID(), 111);

        $this->assertSame(ValidationResultType::VALID(), $result->type());
        $this->assertTrue($result->isSuccessful());
        $this->assertSame(111, $result->drift());
    }

    public function testConstructorUnsuccessfulResult()
    {
        $result = new TotpValidationResult(ValidationResultType::INVALID_PASSWORD());

        $this->assertSame(ValidationResultType::INVALID_PASSWORD(), $result->type());
        $this->assertFalse($result->isSuccessful());
        $this->assertNull($result->drift());
    }

    public function testConstructorFailureSuccessButNoDrift()
    {
        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidResultException');
        new TotpValidationResult(ValidationResultType::VALID());
    }

    public function testConstructorFailureUnsuccessfulWithDrift()
    {
        $this->setExpectedException(__NAMESPACE__ . '\Exception\InvalidResultException');
        new TotpValidationResult(ValidationResultType::INVALID_PASSWORD(), 111);
    }
}

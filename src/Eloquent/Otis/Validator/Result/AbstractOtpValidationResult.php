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

/**
 * An abstract base class for implementing OTP results.
 */
abstract class AbstractOtpValidationResult implements
    OtpValidationResultInterface
{
    /**
     * Construct a new OTP validation result.
     *
     * @param ValidationResultType $type The result type.
     */
    public function __construct(ValidationResultType $type)
    {
        $this->type = $type;
    }

    /**
     * Get the result type.
     *
     * @return ValidationResultType The result type.
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Returns true if this result is a successful result.
     *
     * @return boolean True if this result is a successful result.
     */
    public function isSuccessful()
    {
        return ValidationResultType::VALID() === $this->type();
    }

    private $type;
}

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
 * The interface implemented by OTP validation results.
 */
interface OtpValidationResultInterface
{
    /**
     * Get the result type.
     *
     * @return ValidationResultType The result type.
     */
    public function type();

    /**
     * Returns true if this result is a successful result.
     *
     * @return boolean True if this result is a successful result.
     */
    public function isSuccessful();
}

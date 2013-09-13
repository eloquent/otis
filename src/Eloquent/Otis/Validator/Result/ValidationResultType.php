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

use Eloquent\Enumeration\Enumeration;

/**
 * Describes the possible OTP validation result types.
 */
final class ValidationResultType extends Enumeration
{
    /**
     * The password is valid.
     */
    const VALID = 'valid';

    /**
     * The password is not valid.
     */
    const INVALID_PASSWORD = 'invalid-password';

    /**
     * The password does not match the configured password length.
     */
    const PASSWORD_LENGTH_MISMATCH = 'password-length-mismatch';

    /**
     * The supplied password sequence is empty.
     */
    const EMPTY_PASSWORD_SEQUENCE = 'empty-password-sequence';
}

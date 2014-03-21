<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Result;

/**
 * The interface implemented by multi-factor authentication validation results.
 */
interface MfaValidationResultInterface
{
    /**
     * The credentials are valid.
     */
    const VALID = 'valid';

    /**
     * The credentials are not valid.
     */
    const INVALID_CREDENTIALS = 'invalid-credentials';

    /**
     * The supplied credential does not match the configured length.
     */
    const CREDENTIAL_LENGTH_MISMATCH = 'credential-length-mismatch';

    /**
     * The supplied credential sequence is empty.
     */
    const EMPTY_CREDENTIAL_SEQUENCE = 'empty-credential-sequence';

    /**
     * Get the result type.
     *
     * @return string The result type.
     */
    public function type();

    /**
     * Returns true if this result is a successful result.
     *
     * @return boolean True if this result is a successful result.
     */
    public function isSuccessful();
}

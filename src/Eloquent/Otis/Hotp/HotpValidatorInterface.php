<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp;

/**
 * The interface implemented by HOTP validators.
 */
interface HotpValidatorInterface
{
    /**
     * Validate an HOTP password.
     *
     * @param string       $password       The password to validate.
     * @param string       $secret         The HOTP secret.
     * @param integer      $currentCounter The current counter value.
     * @param integer|null &$newCounter    Will be set to the new counter value.
     * @param integer|null $window         The amount of counter increments to search through for a match.
     *
     * @return boolean True if the password is valid.
     */
    public function validate(
        $password,
        $secret,
        $currentCounter,
        &$newCounter = null,
        $window = null
    );

    /**
     * Validate a sequence of HOTP passwords.
     *
     * @param array<string> $passwords      The password sequence to validate.
     * @param string        $secret         The HOTP secret.
     * @param integer       $currentCounter The current counter value.
     * @param integer|null  &$newCounter    Will be set to the new counter value.
     * @param integer|null  $window         The amount of counter increments to search through for a match.
     *
     * @return boolean True if the password is valid.
     */
    public function validateSequence(
        array $passwords,
        $secret,
        $currentCounter,
        &$newCounter = null,
        $window = null
    );
}

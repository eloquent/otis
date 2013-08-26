<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp;

/**
 * The interface implemented by TOTP validators.
 */
interface TotpValidatorInterface
{
    /**
     * Validate a TOTP password.
     *
     * @param string       $password      The password to validate.
     * @param string       $secret        The TOTP secret.
     * @param integer|null $window        The number of seconds each token is valid for.
     * @param integer|null $pastWindows   The number of past windows to check.
     * @param integer|null $futureWindows The number of future windows to check.
     * @param integer|null &$driftWindows Will be set to the number of windows of clock drift.
     *
     * @return boolean True if the password is valid.
     */
    public function validate(
        $password,
        $secret,
        $window = null,
        $pastWindows = null,
        $futureWindows = null,
        &$driftWindows = null
    );
}

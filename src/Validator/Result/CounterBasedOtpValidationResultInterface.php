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
 * The interface implemented by counter-based one-time password validation
 * results.
 */
interface CounterBasedOtpValidationResultInterface extends
    MfaValidationResultInterface
{
    /**
     * Get the new counter value.
     *
     * @return integer|null The new counter value, or null if the counter should not change.
     */
    public function counter();
}

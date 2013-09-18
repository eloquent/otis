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
 * The interface implemented by counter-based one-time password validation
 * results.
 */
interface TimeBasedOtpValidationResultInterface extends
    OtpValidationResultInterface
{
    /**
     * Get the number of windows of clock drift.
     *
     * @return integer The number of windows of clock drift.
     */
    public function drift();
}

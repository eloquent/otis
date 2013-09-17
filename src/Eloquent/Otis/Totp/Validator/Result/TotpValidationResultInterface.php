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

use Eloquent\Otis\Validator\Result\OtpValidationResultInterface;

/**
 * The interface implemented by TOTP validation results.
 */
interface TotpValidationResultInterface extends OtpValidationResultInterface
{
    /**
     * Get the number of windows of clock drift.
     *
     * @return integer The number of windows of clock drift.
     */
    public function drift();
}

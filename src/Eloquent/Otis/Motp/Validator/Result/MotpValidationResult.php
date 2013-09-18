<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator\Result;

use Eloquent\Otis\Validator\Result\AbstractTimeBasedOtpValidationResult;

/**
 * Represents an mOTP validation result.
 */
class MotpValidationResult extends AbstractTimeBasedOtpValidationResult
    implements MotpValidationResultInterface
{
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Value;

use Eloquent\Otis\Otp\Value\OtpValueInterface;

/**
 * The interface implemented by generated OTP values.
 */
interface HotpValueInterface extends OtpValueInterface
{
    /**
     * Get the truncated value.
     *
     * @link http://tools.ietf.org/html/rfc4226#section-5.3
     *
     * @return integer The truncated value.
     */
    public function truncated();
}

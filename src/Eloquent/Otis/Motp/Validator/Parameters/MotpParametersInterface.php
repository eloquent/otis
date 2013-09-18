<?php // @codeCoverageIgnoreStart

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator\Parameters;

use Eloquent\Otis\Validator\Parameters\OtpParametersInterface;

/**
 * The interface implemented by mOTP validation parameters.
 */
interface MotpParametersInterface extends OtpParametersInterface
{
    /**
     * Get the PIN.
     *
     * @return string The PIN.
     */
    public function pin();
}

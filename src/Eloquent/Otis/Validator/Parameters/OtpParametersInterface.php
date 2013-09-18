<?php // @codeCoverageIgnoreStart

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Parameters;

/**
 * The interface implemented by OTP validation parameters.
 */
interface OtpParametersInterface extends MfaParametersInterface
{
    /**
     * Get the shared secret.
     *
     * @return string The shared secret.
     */
    public function secret();

    /**
     * Get the password.
     *
     * @return string The password.
     */
    public function password();
}

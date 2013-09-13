<?php

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
 * An abstract base class for implementing OTP validation parameters.
 */
abstract class AbstractOtpParameters implements OtpParametersInterface
{
    /**
     * Construct a new OTP validation parameters instance.
     *
     * @param string $secret   The shared secret.
     * @param string $password The password.
     */
    public function __construct($secret, $password)
    {
        $this->secret = $secret;
        $this->password = $password;
    }

    /**
     * Get the shared secret.
     *
     * @return string The shared secret.
     */
    public function secret()
    {
        return $this->secret;
    }

    /**
     * Get the password.
     *
     * @return string The password.
     */
    public function password()
    {
        return $this->password;
    }

    private $secret;
    private $password;
}
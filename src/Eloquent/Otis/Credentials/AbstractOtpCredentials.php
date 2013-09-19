<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Credentials;

/**
 * An abstract base class for implementing one-time password credentials.
 */
abstract class AbstractOtpCredentials implements OtpCredentialsInterface
{
    /**
     * Construct a new one-time password credentials instance.
     *
     * @param string $password The password.
     */
    public function __construct($password)
    {
        $this->password = $password;
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

    private $password;
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Credentials;

/**
 * The interface implemented by one-time password credentials.
 */
interface OtpCredentialsInterface extends MfaCredentialsInterface
{
    /**
     * Get the password.
     *
     * @return string The password.
     */
    public function password();
}

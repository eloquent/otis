<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration;

/**
 * The interface implemented by one-time password authentication configurations.
 */
interface OtpConfigurationInterface extends MfaConfigurationInterface
{
    /**
     * Get the number of password digits.
     *
     * @return integer The number of digits.
     */
    public function digits();

    /**
     * Get the length of the shared secret.
     *
     * @return integer The secret length.
     */
    public function secretLength();
}

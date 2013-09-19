<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters;

/**
 * Represents a set of one-time password authentication shared parameters.
 */
class OtpSharedParameters implements OtpSharedParametersInterface
{
    /**
     * Construct a new one-time password shared parameters instance.
     *
     * @param string $secret The secret.
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Get the secret.
     *
     * @return string The secret.
     */
    public function secret()
    {
        return $this->secret;
    }

    private $secret;
}

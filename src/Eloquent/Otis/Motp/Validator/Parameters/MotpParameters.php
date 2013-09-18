<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator\Parameters;

use Eloquent\Otis\Validator\Parameters\AbstractOtpParameters;

/**
 * Represents mOTP validation parameters.
 */
class MotpParameters extends AbstractOtpParameters implements
    MotpParametersInterface
{
    /**
     * Construct a new mOTP validation parameters instance.
     *
     * @param string $secret   The shared secret.
     * @param string $pin      The PIN.
     * @param string $password The password.
     */
    public function __construct($secret, $pin, $password)
    {
        parent::__construct($secret, $password);

        $this->pin = $pin;
    }

    /**
     * Get the PIN.
     *
     * @return string The PIN.
     */
    public function pin()
    {
        return $this->pin;
    }

    private $pin;
}

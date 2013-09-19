<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Parameters;

use Eloquent\Otis\Parameters\AbstractOtpSharedParameters;

/**
 * Represents a set of mOTP shared parameters.
 */
class MotpSharedParameters extends AbstractOtpSharedParameters
    implements MotpSharedParametersInterface
{
    /**
     * Construct a new mOTP shared parameters instance.
     *
     * @param string $secret The shared secret.
     * @param string $pin    The PIN.
     */
    public function __construct($secret, $pin)
    {
        parent::__construct($secret);

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

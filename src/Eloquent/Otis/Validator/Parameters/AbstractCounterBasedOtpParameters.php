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
 * An abstract base class for implementing counter-based one-time password
 * validation parameters.
 */
abstract class AbstractCounterBasedOtpParameters extends AbstractOtpParameters
    implements CounterBasedOtpParametersInterface
{
    /**
     * Construct a new HOTP validation parameters instance.
     *
     * @param string  $secret   The shared secret.
     * @param string  $password The password.
     * @param integer $counter  The current counter value.
     */
    public function __construct($secret, $password, $counter)
    {
        parent::__construct($secret, $password);

        $this->counter = $counter;
    }

    /**
     * Get the current counter value.
     *
     * @return integer The current counter value.
     */
    public function counter()
    {
        return $this->counter;
    }

    private $counter;
}

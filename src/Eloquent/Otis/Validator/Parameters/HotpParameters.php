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
 * Represents HOTP validation parameters.
 */
class HotpParameters extends AbstractOtpParameters implements
    HotpParametersInterface
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

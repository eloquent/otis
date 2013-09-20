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
 * Represents a set of counter-based one-time password authentication shared
 * parameters.
 */
class CounterBasedOtpSharedParameters extends AbstractOtpSharedParameters
    implements CounterBasedOtpSharedParametersInterface
{
    /**
     * Construct a new counter-based one-time password shared parameters
     * instance.
     *
     * @param string       $secret  The shared secret.
     * @param integer|null $counter The current counter value.
     */
    public function __construct($secret, $counter = null)
    {
        if (null === $counter) {
            $counter = 1;
        }

        parent::__construct($secret);

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

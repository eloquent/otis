<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Result;

/**
 * Represents a time-based one-time password validation result.
 */
class TimeBasedOtpValidationResult extends MfaValidationResult implements
    TimeBasedOtpValidationResultInterface
{
    /**
     * Construct a new time-based one-time password validation result.
     *
     * @param string       $type  The result type.
     * @param boolean|null $drift The number of windows of clock drift, or null if unsuccessful.
     *
     * @throws Exception\InvalidMfaResultException If the supplied arguments constitute an invalid result.
     */
    public function __construct($type, $drift = null)
    {
        if (
            (static::VALID === $type && null === $drift) ||
            (static::VALID !== $type && null !== $drift)
        ) {
            throw new Exception\InvalidMfaResultException;
        }

        parent::__construct($type);

        $this->drift = $drift;
    }

    /**
     * Get the number of windows of clock drift.
     *
     * @return integer|null The number of windows of clock drift, or null if unsuccessful.
     */
    public function drift()
    {
        return $this->drift;
    }

    private $drift;
}

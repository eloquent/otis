<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Validator\Result;

use Eloquent\Otis\Validator\Result\AbstractMfaValidationResult;
use Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException;

/**
 * Represents a TOTP validation result.
 */
class TotpValidationResult extends AbstractMfaValidationResult implements
    TotpValidationResultInterface
{
    /**
     * Construct a new TOTP validation result.
     *
     * @param string       $type  The result type.
     * @param boolean|null $drift The number of windows of clock drift, or null if unsuccessful.
     *
     * @throws InvalidMfaResultException If the supplied arguments constitute an invalid result.
     */
    public function __construct($type, $drift = null)
    {
        if (
            (static::VALID === $type && null === $drift) ||
            (static::VALID !== $type && null !== $drift)
        ) {
            throw new InvalidMfaResultException;
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

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Validator\Result;

use Eloquent\Otis\Validator\Result\AbstractMfaValidationResult;
use Eloquent\Otis\Validator\Result\Exception\InvalidMfaResultException;

/**
 * Represents a HOTP validation result.
 */
class HotpValidationResult extends AbstractMfaValidationResult implements
    HotpValidationResultInterface
{
    /**
     * Construct a new HOTP validation result.
     *
     * @param string       $type    The result type.
     * @param integer|null $counter The new counter value, or null if the counter should not change.
     *
     * @throws InvalidMfaResultException If the supplied arguments constitute an invalid result.
     */
    public function __construct($type, $counter = null)
    {
        if (
            (static::VALID === $type && null === $counter) ||
            (static::VALID !== $type && null !== $counter)
        ) {
            throw new InvalidMfaResultException;
        }

        parent::__construct($type);

        $this->counter = $counter;
    }

    /**
     * Get the new counter value.
     *
     * @return integer|null The new counter value, or null if the counter should not change.
     */
    public function counter()
    {
        return $this->counter;
    }

    private $counter;
}

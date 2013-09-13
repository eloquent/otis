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
 * Represents a HOTP validation result.
 */
class HotpValidationResult extends AbstractOtpValidationResult implements
    HotpValidationResultInterface
{
    /**
     * Construct a new HOTP validation result.
     *
     * @param ValidationResultType $type    The result type.
     * @param integer|null         $counter The new counter value, or null if the counter should not change.
     *
     * @throws Exception\InvalidResultException If the supplied arguments constitute an invalid result.
     */
    public function __construct(ValidationResultType $type, $counter = null)
    {
        if (
            (ValidationResultType::VALID() === $type && null === $counter) ||
            (ValidationResultType::VALID() !== $type && null !== $counter)
        ) {
            throw new Exception\InvalidResultException;
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

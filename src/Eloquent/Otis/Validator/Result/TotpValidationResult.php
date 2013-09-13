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
 * Represents a TOTP validation result.
 */
class TotpValidationResult extends AbstractOtpValidationResult implements
    TotpValidationResultInterface
{
    /**
     * Construct a new TOTP validation result.
     *
     * @param ValidationResultType $type  The result type.
     * @param boolean|null         $drift The number of windows of clock drift, or null if unsuccessful.
     *
     * @throws Exception\InvalidResultException If the supplied arguments constitute an invalid result.
     */
    public function __construct(
        ValidationResultType $type,
        $drift = null
    ) {
        if (
            (ValidationResultType::VALID() === $type && null === $drift) ||
            (ValidationResultType::VALID() !== $type && null !== $drift)
        ) {
            throw new Exception\InvalidResultException;
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

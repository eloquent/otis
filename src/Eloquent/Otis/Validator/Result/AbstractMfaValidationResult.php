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
 * An abstract base class for implementing multi-factor authentication results.
 */
abstract class AbstractMfaValidationResult implements
    OtpValidationResultInterface
{
    /**
     * Construct a new multi-factor authentication validation result.
     *
     * @param string $type The result type.
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * Get the result type.
     *
     * @return string The result type.
     */
    public function type()
    {
        return $this->type;
    }

    /**
     * Returns true if this result is a successful result.
     *
     * @return boolean True if this result is a successful result.
     */
    public function isSuccessful()
    {
        return static::VALID === $this->type();
    }

    private $type;
}

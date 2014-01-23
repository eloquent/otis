<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator;

use Eloquent\Otis\Otp\Value\OtpValueGeneratorInterface;

/**
 * An abstract base class for implementing one-time password validators.
 */
abstract class AbstractOtpValidator implements MfaValidatorInterface
{
    /**
     * Construct a new one-time password validator.
     *
     * @param OtpValueGeneratorInterface $generator The value generator to use.
     */
    public function __construct(OtpValueGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }

    /**
     * Get the value generator.
     *
     * @return OtpValueGeneratorInterface The value generator.
     */
    public function generator()
    {
        return $this->generator;
    }

    private $generator;
}

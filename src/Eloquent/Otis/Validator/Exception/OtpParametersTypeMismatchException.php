<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Validator\Exception;

use Eloquent\Otis\Validator\Parameters\OtpParametersInterface;
use Exception;

/**
 * The supplied OTP validation parameters do not match the type required by the
 * validator.
 */
class OtpParametersTypeMismatchException extends Exception
{
    /**
     * Construct a new OTP parameters type mismatch exception.
     *
     * @param string                 $requiredType The required parameters type.
     * @param OtpParametersInterface $parameters   The supplied parameters.
     * @param Exception|null         $previous     The cause, if available.
     */
    public function __construct(
        $requiredType,
        OtpParametersInterface $parameters,
        Exception $previous = null
    ) {
        $this->requiredType = $requiredType;
        $this->parameters = $parameters;

        parent::__construct(
            sprintf(
                'Unexpected OTP parameters type %s, expected %s.',
                var_export(get_class($parameters), true),
                var_export($requiredType, true)
            ),
            0,
            $previous
        );
    }

    /**
     * Get the required parameters type.
     *
     * @return string The required parameters type.
     */
    public function requiredType()
    {
        return $this->requiredType;
    }

    /**
     * Get the supplied parameters.
     *
     * @return OtpParametersInterface The supplied parameters.
     */
    public function parameters()
    {
        return $this->parameters;
    }

    private $requiredType;
    private $parameters;
}

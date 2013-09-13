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

use Eloquent\Otis\Configuration\OtpConfigurationInterface;

/**
 * The interface implemented by generic OTP validators.
 */
interface OtpValidatorInterface
{
    /**
     * Validate an OTP password.
     *
     * @param OtpConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\OtpParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\OtpValidationResultInterface            The validation result.
     * @throws Exception\UnsupportedOtpConfigurationException If the OTP configuration is not supported.
     * @throws Exception\OtpParametersTypeMismatchException   If the OTP parameters are the wrong type for the validator.
     */
    public function validate(
        OtpConfigurationInterface $configuration,
        Parameters\OtpParametersInterface $parameters
    );
}

<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Totp\Validator;

use Eloquent\Otis\Credentials\OtpCredentialsInterface;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParametersInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;
use Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResultInterface;

/**
 * The interface implemented by TOTP validators.
 */
interface TotpValidatorInterface
{
    /**
     * Validate a TOTP password.
     *
     * @param TotpConfigurationInterface            $configuration The configuration to use for validation.
     * @param TimeBasedOtpSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param OtpCredentialsInterface               $credentials   The credentials to validate.
     *
     * @return TimeBasedOtpValidationResultInterface The validation result.
     */
    public function validateTotp(
        TotpConfigurationInterface $configuration,
        TimeBasedOtpSharedParametersInterface $shared,
        OtpCredentialsInterface $credentials
    );
}
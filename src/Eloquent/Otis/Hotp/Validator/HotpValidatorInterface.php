<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Hotp\Validator;

use Eloquent\Otis\Credentials\OtpCredentialsInterface;
use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;

/**
 * The interface implemented by HOTP validators.
 */
interface HotpValidatorInterface
{
    /**
     * Validate an HOTP password.
     *
     * @param HotpConfigurationInterface               $configuration The configuration to use for validation.
     * @param CounterBasedOtpSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param OtpCredentialsInterface                  $credentials   The credentials to validate.
     *
     * @return Result\HotpValidationResultInterface The validation result.
     */
    public function validateHotp(
        HotpConfigurationInterface $configuration,
        CounterBasedOtpSharedParametersInterface $shared,
        OtpCredentialsInterface $credentials
    );

    /**
     * Validate a sequence of HOTP passwords.
     *
     * @param HotpConfigurationInterface               $configuration      The configuration to use for validation.
     * @param CounterBasedOtpSharedParametersInterface $shared             The shared parameters to use for validation.
     * @param array<OtpCredentialsInterface>           $credentialSequence The sequence of credentials to validate.
     *
     * @return Result\HotpValidationResultInterface The validation result.
     */
    public function validateHotpSequence(
        HotpConfigurationInterface $configuration,
        CounterBasedOtpSharedParametersInterface $shared,
        array $credentialSequence
    );
}

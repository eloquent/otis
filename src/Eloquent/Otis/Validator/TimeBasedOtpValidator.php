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

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Credentials\MfaCredentialsInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResult;

/**
 * Validates time-based one-time passwords.
 */
class TimeBasedOtpValidator extends AbstractOtpValidator
{
    /**
     * Validate a set of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface    $configuration The configuration to use for validation.
     * @param MfaSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param MfaCredentialsInterface      $credentials   The credentials to validate.
     *
     * @return Result\MfaValidationResultInterface The validation result.
     */
    public function validate(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        MfaCredentialsInterface $credentials
    ) {
        if (strlen($credentials->password()) !== $configuration->digits()) {
            return new TimeBasedOtpValidationResult(
                TimeBasedOtpValidationResult::CREDENTIAL_LENGTH_MISMATCH
            );
        }

        for (
            $i = -$configuration->pastWindows();
            $i <= $configuration->futureWindows();
            ++$i
        ) {
            $currentShared = clone $shared;
            $currentShared->setTime(
                $shared->time() + ($i * $configuration->window())
            );

            $value = $this->generator()->generate(
                $configuration,
                $currentShared
            );

            if (
                $credentials->password() === $value->string(
                    $configuration->digits()
                )
            ) {
                return new TimeBasedOtpValidationResult(
                    TimeBasedOtpValidationResult::VALID,
                    $i
                );
            }
        }

        return new TimeBasedOtpValidationResult(
            TimeBasedOtpValidationResult::INVALID_CREDENTIALS
        );
    }
}

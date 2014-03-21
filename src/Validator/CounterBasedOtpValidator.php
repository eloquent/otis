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
use Eloquent\Otis\Validator\Result\CounterBasedOtpValidationResult;

/**
 * Validates counter-based one-time passwords.
 */
class CounterBasedOtpValidator extends AbstractOtpValidator implements
    MfaSequenceValidatorInterface
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
            return new CounterBasedOtpValidationResult(
                CounterBasedOtpValidationResult::CREDENTIAL_LENGTH_MISMATCH
            );
        }

        for (
            $counter = $shared->counter();
            $counter <= $shared->counter() + $configuration->window();
            ++$counter
        ) {
            $currentShared = clone $shared;
            $currentShared->setCounter($counter);

            $value = $this->generator()->generate(
                $configuration,
                $currentShared
            );

            if (
                $credentials->password() === $value->string(
                    $configuration->digits()
                )
            ) {
                return new CounterBasedOtpValidationResult(
                    CounterBasedOtpValidationResult::VALID,
                    $counter + 1
                );
            }
        }

        return new CounterBasedOtpValidationResult(
            CounterBasedOtpValidationResult::INVALID_CREDENTIALS
        );
    }

    /**
     * Validate a sequence of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface      $configuration      The configuration to use for validation.
     * @param MfaSharedParametersInterface   $shared             The shared parameters to use for validation.
     * @param array<MfaCredentialsInterface> $credentialSequence The sequence of credentials to validate.
     *
     * @return Result\MfaValidationResultInterface The validation result.
     */
    public function validateSequence(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        array $credentialSequence
    ) {
        if (count($credentialSequence) < 1) {
            return new CounterBasedOtpValidationResult(
                CounterBasedOtpValidationResult::EMPTY_CREDENTIAL_SEQUENCE
            );
        }

        $first = true;
        $counter = $shared->counter();
        foreach ($credentialSequence as $credentials) {
            if ($first) {
                $window = $configuration->window();
            } else {
                $window = 0;
            }

            $currentConfiguration = clone $configuration;
            $currentConfiguration->setWindow($window);

            $currentShared = clone $shared;
            $currentShared->setCounter($counter);

            $result = $this->validate(
                $currentConfiguration,
                $currentShared,
                $credentials
            );

            if (!$result->isSuccessful()) {
                break;
            }

            $counter = $result->counter();
            $first = false;
        }

        return $result;
    }
}

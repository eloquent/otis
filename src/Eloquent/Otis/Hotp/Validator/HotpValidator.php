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

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Credentials\MfaCredentialsInterface;
use Eloquent\Otis\Credentials\OtpCredentialsInterface;
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\Configuration\HotpConfigurationInterface;
use Eloquent\Otis\Hotp\Value\HotpValueGenerator;
use Eloquent\Otis\Hotp\Value\HotpValueGeneratorInterface;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParametersInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Validator\MfaSequenceValidatorInterface;
use Eloquent\Otis\Validator\Result\CounterBasedOtpValidationResult;
use Eloquent\Otis\Validator\Result\CounterBasedOtpValidationResultInterface;
use Eloquent\Otis\Validator\Result\MfaValidationResultInterface;

/**
 * Validates HOTP passwords.
 */
class HotpValidator implements
    MfaSequenceValidatorInterface,
    HotpValidatorInterface
{
    /**
     * Construct a new HOTP validator.
     *
     * @param HotpValueGeneratorInterface|null $generator The generator to use.
     */
    public function __construct(HotpValueGeneratorInterface $generator = null)
    {
        if (null === $generator) {
            $generator = new HotpValueGenerator;
        }

        $this->generator = $generator;
    }

    /**
     * Get the generator.
     *
     * @return HotpValueGeneratorInterface The generator.
     */
    public function generator()
    {
        return $this->generator;
    }

    /**
     * Validate a set of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface    $configuration The configuration to use for validation.
     * @param MfaSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param MfaCredentialsInterface      $credentials   The credentials to validate.
     *
     * @return MfaValidationResultInterface The validation result.
     */
    public function validate(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        MfaCredentialsInterface $credentials
    ) {
        return $this->validateHotp($configuration, $shared, $credentials);
    }

    /**
     * Validate a sequence of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface      $configuration      The configuration to use for validation.
     * @param MfaSharedParametersInterface   $shared             The shared parameters to use for validation.
     * @param array<MfaCredentialsInterface> $credentialSequence The sequence of credentials to validate.
     *
     * @return MfaValidationResultInterface The validation result.
     */
    public function validateSequence(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        array $credentialSequence
    ) {
        return $this->validateHotpSequence(
            $configuration,
            $shared,
            $credentialSequence
        );
    }

    /**
     * Validate an HOTP password.
     *
     * @param HotpConfigurationInterface               $configuration The configuration to use for validation.
     * @param CounterBasedOtpSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param OtpCredentialsInterface                  $credentials   The credentials to validate.
     *
     * @return CounterBasedOtpValidationResultInterface The validation result.
     */
    public function validateHotp(
        HotpConfigurationInterface $configuration,
        CounterBasedOtpSharedParametersInterface $shared,
        OtpCredentialsInterface $credentials
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

            $value = $this->generator()->generateHotp(
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
     * Validate a sequence of HOTP passwords.
     *
     * @param HotpConfigurationInterface               $configuration      The configuration to use for validation.
     * @param CounterBasedOtpSharedParametersInterface $shared             The shared parameters to use for validation.
     * @param array<OtpCredentialsInterface>           $credentialSequence The sequence of credentials to validate.
     *
     * @return CounterBasedOtpValidationResultInterface The validation result.
     */
    public function validateHotpSequence(
        HotpConfigurationInterface $configuration,
        CounterBasedOtpSharedParametersInterface $shared,
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

            $currentShared = clone $shared;
            $currentShared->setCounter($counter);

            $result = $this->validateHotp(
                new HotpConfiguration(
                    $configuration->digits(),
                    $window,
                    $configuration->initialCounter(),
                    $configuration->secretLength(),
                    $configuration->algorithm()
                ),
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

    private $generator;
}

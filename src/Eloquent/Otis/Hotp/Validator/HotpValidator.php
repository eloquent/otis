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
use Eloquent\Otis\Hotp\Generator\HotpGenerator;
use Eloquent\Otis\Hotp\Generator\HotpGeneratorInterface;
use Eloquent\Otis\Hotp\Parameters\HotpSharedParameters;
use Eloquent\Otis\Hotp\Parameters\HotpSharedParametersInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException;
use Eloquent\Otis\Validator\MfaValidatorInterface;
use Eloquent\Otis\Validator\Result\MfaValidationResultInterface;

/**
 * Validates HOTP passwords.
 */
class HotpValidator implements MfaValidatorInterface, HotpValidatorInterface
{
    /**
     * Construct a new HOTP validator.
     *
     * @param HotpGeneratorInterface|null $generator The generator to use.
     */
    public function __construct(HotpGeneratorInterface $generator = null)
    {
        if (null === $generator) {
            $generator = new HotpGenerator;
        }

        $this->generator = $generator;
    }

    /**
     * Get the generator.
     *
     * @return HotpGeneratorInterface The generator.
     */
    public function generator()
    {
        return $this->generator;
    }

    /**
     * Returns true if this validator supports the supplied combination of
     * configuration, shared parameters, and credentials.
     *
     * @param MfaConfigurationInterface    $configuration The configuration to use for validation.
     * @param MfaSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param MfaCredentialsInterface      $credentials   The credentials to validate.
     *
     * @return boolean True if this validator supports the supplied combination.
     */
    public function supports(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        MfaCredentialsInterface $credentials
    ) {
        return $configuration instanceof HotpConfigurationInterface &&
            $shared instanceof HotpSharedParametersInterface &&
            $credentials instanceof OtpCredentialsInterface;
    }

    /**
     * Validate a set of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface    $configuration The configuration to use for validation.
     * @param MfaSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param MfaCredentialsInterface      $credentials   The credentials to validate.
     *
     * @return MfaValidationResultInterface       The validation result.
     * @throws UnsupportedMfaCombinationException If the combination of configuration, shared parameters, and credentials is not supported.
     */
    public function validate(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        MfaCredentialsInterface $credentials
    ) {
        if (!$this->supports($configuration, $shared, $credentials)) {
            throw new UnsupportedMfaCombinationException(
                $configuration,
                $shared,
                $credentials
            );
        }

        return $this->validateHotp($configuration, $shared, $credentials);
    }

    /**
     * Validate an HOTP password.
     *
     * @param HotpConfigurationInterface    $configuration The configuration to use for validation.
     * @param HotpSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param OtpCredentialsInterface       $credentials   The credentials to validate.
     *
     * @return Result\HotpValidationResultInterface The validation result.
     */
    public function validateHotp(
        HotpConfigurationInterface $configuration,
        HotpSharedParametersInterface $shared,
        OtpCredentialsInterface $credentials
    ) {
        if (strlen($credentials->password()) !== $configuration->digits()) {
            return new Result\HotpValidationResult(
                Result\HotpValidationResult::CREDENTIAL_LENGTH_MISMATCH
            );
        }

        for (
            $counter = $shared->counter();
            $counter <= $shared->counter() + $configuration->window();
            ++$counter
        ) {
            $value = $this->generator()->generate(
                $shared->secret(),
                $counter,
                $configuration->algorithm()
            );

            if (
                $credentials->password() === $value->string(
                    $configuration->digits()
                )
            ) {
                return new Result\HotpValidationResult(
                    Result\HotpValidationResult::VALID,
                    $counter + 1
                );
            }
        }

        return new Result\HotpValidationResult(
            Result\HotpValidationResult::INVALID_CREDENTIALS
        );
    }

    /**
     * Validate a sequence of HOTP passwords.
     *
     * @param HotpConfigurationInterface     $configuration      The configuration to use for validation.
     * @param HotpSharedParametersInterface  $shared             The shared parameters to use for validation.
     * @param array<OtpCredentialsInterface> $credentialSequence The sequence of credentials to validate.
     *
     * @return Result\HotpValidationResultInterface The validation result.
     */
    public function validateHotpSequence(
        HotpConfigurationInterface $configuration,
        HotpSharedParametersInterface $shared,
        array $credentialSequence
    ) {
        if (count($credentialSequence) < 1) {
            return new Result\HotpValidationResult(
                Result\HotpValidationResult::EMPTY_CREDENTIAL_SEQUENCE
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

            $result = $this->validateHotp(
                new HotpConfiguration(
                    $configuration->digits(),
                    $window,
                    $configuration->initialCounter(),
                    $configuration->secretLength(),
                    $configuration->algorithm()
                ),
                new HotpSharedParameters($shared->secret(), $counter),
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

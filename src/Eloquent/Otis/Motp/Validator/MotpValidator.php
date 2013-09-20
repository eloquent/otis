<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Validator;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Credentials\MfaCredentialsInterface;
use Eloquent\Otis\Credentials\OtpCredentialsInterface;
use Eloquent\Otis\Exception\UnsupportedArgumentsException;
use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;
use Eloquent\Otis\Motp\Generator\MotpGenerator;
use Eloquent\Otis\Motp\Generator\MotpGeneratorInterface;
use Eloquent\Otis\Motp\Parameters\MotpSharedParameters;
use Eloquent\Otis\Motp\Parameters\MotpSharedParametersInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Validator\MfaValidatorInterface;
use Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResult;
use Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResultInterface;

/**
 * Validates mOTP passwords.
 */
class MotpValidator implements MfaValidatorInterface, MotpValidatorInterface
{
    /**
     * Construct a new mOTP validator.
     *
     * @param MotpGeneratorInterface|null $generator The generator to use.
     */
    public function __construct(MotpGeneratorInterface $generator = null)
    {
        if (null === $generator) {
            $generator = new MotpGenerator;
        }

        $this->generator = $generator;
    }

    /**
     * Get the generator.
     *
     * @return MotpGeneratorInterface The generator.
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
        return $configuration instanceof MotpConfigurationInterface &&
            $shared instanceof MotpSharedParametersInterface &&
            $credentials instanceof OtpCredentialsInterface;
    }

    /**
     * Validate a set of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface    $configuration The configuration to use for validation.
     * @param MfaSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param MfaCredentialsInterface      $credentials   The credentials to validate.
     *
     * @return MfaValidationResultInterface  The validation result.
     * @throws UnsupportedArgumentsException If the combination of configuration, shared parameters, and credentials is not supported.
     */
    public function validate(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        MfaCredentialsInterface $credentials
    ) {
        if (!$this->supports($configuration, $shared, $credentials)) {
            throw new UnsupportedArgumentsException;
        }

        return $this->validateMotp($configuration, $shared, $credentials);
    }

    /**
     * Validate an mOTP password.
     *
     * @param MotpConfigurationInterface    $configuration The configuration to use for validation.
     * @param MotpSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param OtpCredentialsInterface       $credentials   The credentials to validate.
     *
     * @return TimeBasedOtpValidationResultInterface The validation result.
     */
    public function validateMotp(
        MotpConfigurationInterface $configuration,
        MotpSharedParametersInterface $shared,
        OtpCredentialsInterface $credentials
    ) {
        if (strlen($credentials->password()) !== 6) {
            return new TimeBasedOtpValidationResult(
                TimeBasedOtpValidationResult::CREDENTIAL_LENGTH_MISMATCH
            );
        }

        for (
            $i = -$configuration->pastWindows();
            $i <= $configuration->futureWindows();
            ++$i
        ) {
            $value = $this->generator()->generateMotp(
                $configuration,
                new MotpSharedParameters(
                    $shared->secret(),
                    $shared->pin(),
                    $shared->time() + ($i * 10)
                )
            );

            if ($credentials->password() === $value) {
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

    private $generator;
}

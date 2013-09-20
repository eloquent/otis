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

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Credentials\MfaCredentialsInterface;
use Eloquent\Otis\Credentials\OtpCredentialsInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Parameters\OtpSharedParametersInterface;
use Eloquent\Otis\Totp\Configuration\TotpConfigurationInterface;
use Eloquent\Otis\Totp\Generator\TotpGenerator;
use Eloquent\Otis\Totp\Generator\TotpGeneratorInterface;
use Eloquent\Otis\Validator\Exception\UnsupportedMfaCombinationException;
use Eloquent\Otis\Validator\MfaValidatorInterface;
use Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResult;
use Eloquent\Otis\Validator\Result\TimeBasedOtpValidationResultInterface;
use Icecave\Isolator\Isolator;

/**
 * Validates TOTP passwords.
 */
class TotpValidator implements MfaValidatorInterface, TotpValidatorInterface
{
    /**
     * Construct a new TOTP validator.
     *
     * @param TotpGeneratorInterface|null $generator The generator to use.
     * @param Isolator|null               $isolator  The isolator to use.
     */
    public function __construct(
        TotpGeneratorInterface $generator = null,
        Isolator $isolator = null
    ) {
        if (null === $generator) {
            $generator = new TotpGenerator;
        }

        $this->generator = $generator;
        $this->isolator = Isolator::get($isolator);
    }

    /**
     * Get the generator.
     *
     * @return TotpGeneratorInterface The generator.
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
        return $configuration instanceof TotpConfigurationInterface &&
            $shared instanceof OtpSharedParametersInterface &&
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
            throw new UnsupportedMfaCombinationException;
        }

        return $this->validateTotp($configuration, $shared, $credentials);
    }

    /**
     * Validate a TOTP password.
     *
     * @param TotpConfigurationInterface   $configuration The configuration to use for validation.
     * @param OtpSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param OtpCredentialsInterface      $credentials   The credentials to validate.
     *
     * @return TimeBasedOtpValidationResultInterface The validation result.
     */
    public function validateTotp(
        TotpConfigurationInterface $configuration,
        OtpSharedParametersInterface $shared,
        OtpCredentialsInterface $credentials
    ) {
        if (strlen($credentials->password()) !== $configuration->digits()) {
            return new TimeBasedOtpValidationResult(
                TimeBasedOtpValidationResult::CREDENTIAL_LENGTH_MISMATCH
            );
        }

        $time = $this->isolator()->time();

        for (
            $i = -$configuration->pastWindows();
            $i <= $configuration->futureWindows();
            ++$i
        ) {
            $value = $this->generator()->generate(
                $shared->secret(),
                $configuration->window(),
                $time + ($i * $configuration->window()),
                $configuration->algorithm()
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

    /**
     * Get the isolator.
     *
     * @return Isolator The isolator.
     */
    protected function isolator()
    {
        return $this->isolator;
    }

    private $generator;
    private $isolator;
}

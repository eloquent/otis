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
use Eloquent\Otis\Exception\UnsupportedArgumentsException;
use Eloquent\Otis\Hotp\Validator\HotpValidator;
use Eloquent\Otis\Motp\Validator\MotpValidator;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;
use Eloquent\Otis\Totp\Validator\TotpValidator;

/**
 * A generic multi-factor authentication validator.
 */
class MfaValidator implements MfaSequenceValidatorInterface
{
    /**
     * Construct a new multi-factor authentication validator.
     *
     * @param array<MfaValidatorInterface>|null $validators The validators to aggregate.
     */
    public function __construct(array $validators = null)
    {
        if (null === $validators) {
            $validators = array(
                new TotpValidator,
                new HotpValidator,
                new MotpValidator,
            );
        }

        $this->validators = $validators;
    }

    /**
     * Get the aggregated validators.
     *
     * @return array<MfaValidatorInterface> The aggregated validators.
     */
    public function validators()
    {
        return $this->validators;
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
        foreach ($this->validators() as $validator) {
            if ($validator->supports($configuration, $shared, $credentials)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate a set of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface    $configuration The configuration to use for validation.
     * @param MfaSharedParametersInterface $shared        The shared parameters to use for validation.
     * @param MfaCredentialsInterface      $credentials   The credentials to validate.
     *
     * @return Result\MfaValidationResultInterface The validation result.
     * @throws UnsupportedArgumentsException       If the combination of configuration, shared parameters, and credentials is not supported.
     */
    public function validate(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        MfaCredentialsInterface $credentials
    ) {
        foreach ($this->validators() as $validator) {
            if ($validator->supports($configuration, $shared, $credentials)) {
                return $validator->validate(
                    $configuration,
                    $shared,
                    $credentials
                );
            }
        }

        throw new UnsupportedArgumentsException;
    }

    /**
     * Returns true if this validator supports the supplied combination of
     * configuration, shared parameters, and credential sequence.
     *
     * @param MfaConfigurationInterface      $configuration      The configuration to use for validation.
     * @param MfaSharedParametersInterface   $shared             The shared parameters to use for validation.
     * @param array<MfaCredentialsInterface> $credentialSequence The sequence of credentials to validate.
     *
     * @return boolean True if this validator supports the supplied combination.
     */
    public function supportsSequence(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        array $credentialSequence
    ) {
        foreach ($this->validators() as $validator) {
            if (
                $validator instanceof MfaSequenceValidatorInterface &&
                $validator->supportsSequence(
                    $configuration,
                    $shared,
                    $credentialSequence
                )
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate a sequence of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface      $configuration      The configuration to use for validation.
     * @param MfaSharedParametersInterface   $shared             The shared parameters to use for validation.
     * @param array<MfaCredentialsInterface> $credentialSequence The sequence of credentials to validate.
     *
     * @return Result\MfaValidationResultInterface The validation result.
     * @throws UnsupportedArgumentsException       If the combination of configuration, shared parameters, and credentials is not supported.
     */
    public function validateSequence(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        array $credentialSequence
    ) {
        foreach ($this->validators() as $validator) {
            if (
                $validator instanceof MfaSequenceValidatorInterface &&
                $validator->supportsSequence(
                    $configuration,
                    $shared,
                    $credentialSequence
                )
            ) {
                return $validator->validateSequence(
                    $configuration,
                    $shared,
                    $credentialSequence
                );
            }
        }

        throw new UnsupportedArgumentsException;
    }

    private $validators;
}

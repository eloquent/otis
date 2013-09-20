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

/**
 * The interface implemented by generic multi-factor authentication validators
 * that can validate credential sequences.
 */
interface MfaSequenceValidatorInterface extends MfaValidatorInterface
{
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
    );

    /**
     * Validate a sequence of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface      $configuration      The configuration to use for validation.
     * @param MfaSharedParametersInterface   $shared             The shared parameters to use for validation.
     * @param array<MfaCredentialsInterface> $credentialSequence The sequence of credentials to validate.
     *
     * @return Result\MfaValidationResultInterface          The validation result.
     * @throws Exception\UnsupportedMfaCombinationException If the combination of configuration, shared parameters, and credentials is not supported.
     */
    public function validateSequence(
        MfaConfigurationInterface $configuration,
        MfaSharedParametersInterface $shared,
        array $credentialSequence
    );
}

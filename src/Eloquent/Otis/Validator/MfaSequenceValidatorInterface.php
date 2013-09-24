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
    );
}

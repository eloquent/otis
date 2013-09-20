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
use Eloquent\Otis\Validator\Exception\UnsupportedArgumentsException;

/**
 * The interface implemented by generic multi-factor authentication validators.
 */
interface MfaValidatorInterface
{
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
    );

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
    );
}

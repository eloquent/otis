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

/**
 * The interface implemented by generic multi-factor authentication validators.
 */
interface MfaValidatorInterface
{
    /**
     * Returns true if this validator supports the supplied combination of
     * configuration and parameters.
     *
     * @param MfaConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\MfaParametersInterface $parameters    The parameters to validate.
     *
     * @return boolean True if this validator supports the supplied combination.
     */
    public function supports(
        MfaConfigurationInterface $configuration,
        Parameters\MfaParametersInterface $parameters
    );

    /**
     * Validate a set of multi-factor authentication parameters.
     *
     * @param MfaConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\MfaParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\MfaValidationResultInterface          The validation result.
     * @throws Exception\UnsupportedMfaCombinationException If the combination of configuration and parameters is not supported.
     */
    public function validate(
        MfaConfigurationInterface $configuration,
        Parameters\MfaParametersInterface $parameters
    );
}

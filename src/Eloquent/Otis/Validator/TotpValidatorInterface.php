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

use Eloquent\Otis\Configuration\TotpConfigurationInterface;

/**
 * The interface implemented by TOTP validators.
 */
interface TotpValidatorInterface
{
    /**
     * Validate a TOTP password.
     *
     * @param TotpConfigurationInterface         $configuration The configuration to use for validation.
     * @param Parameters\TotpParametersInterface $parameters    The parameters to validate.
     *
     * @return Result\TotpValidationResultInterface The validation result.
     */
    public function validate(
        TotpConfigurationInterface $configuration,
        Parameters\TotpParametersInterface $parameters
    );
}

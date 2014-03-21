<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Driver;

use Eloquent\Otis\Parameters\Generator\MfaSharedParametersGeneratorInterface;
use Eloquent\Otis\Uri\Initialization\InitializationUriFactoryInterface;
use Eloquent\Otis\Validator\MfaValidatorInterface;

/**
 * The interface implemented by multi-factor authentication drivers.
 */
interface MfaDriverInterface
{
    /**
     * Get the validator.
     *
     * @return MfaValidatorInterface The validator.
     */
    public function validator();

    /**
     * Get the shared parameters generator.
     *
     * @return MfaSharedParametersGeneratorInterface The shared parameters generator.
     */
    public function sharedParametersGenerator();

    /**
     * Get the initialization URI factory.
     *
     * @return InitializationUriFactoryInterface|null The initialization URI factory, or null if not supported.
     */
    public function initializationUriFactory();
}

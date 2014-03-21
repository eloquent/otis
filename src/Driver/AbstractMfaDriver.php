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
 * An abstract base class for implementing multi-factor authentication drivers.
 */
abstract class AbstractMfaDriver implements MfaDriverInterface
{
    /**
     * Construct a new multi-factor authentication driver.
     *
     * @param MfaValidatorInterface                      $validator                 The validator to use.
     * @param MfaSharedParametersGeneratorInterface|null $sharedParametersGenerator The shared parameters generator to use.
     * @param InitializationUriFactoryInterface|null     $initializationUriFactory  The initialization URI factory to use, or null if not supported.
     */
    public function __construct(
        MfaValidatorInterface $validator,
        MfaSharedParametersGeneratorInterface $sharedParametersGenerator,
        InitializationUriFactoryInterface $initializationUriFactory = null
    ) {
        $this->validator = $validator;
        $this->sharedParametersGenerator = $sharedParametersGenerator;
        $this->initializationUriFactory = $initializationUriFactory;
    }

    /**
     * Get the validator.
     *
     * @return MfaValidatorInterface The validator.
     */
    public function validator()
    {
        return $this->validator;
    }

    /**
     * Get the shared parameters generator.
     *
     * @return MfaSharedParametersGeneratorInterface The shared parameters generator.
     */
    public function sharedParametersGenerator()
    {
        return $this->sharedParametersGenerator;
    }

    /**
     * Get the initialization URI factory.
     *
     * @return InitializationUriFactoryInterface|null The initialization URI factory, or null if not supported.
     */
    public function initializationUriFactory()
    {
        return $this->initializationUriFactory;
    }

    private $validator;
    private $sharedParametersGenerator;
    private $initializationUriFactory;
}

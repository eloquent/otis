<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Parameters\Generator;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;
use Eloquent\Otis\Parameters\MfaSharedParametersInterface;

/**
 * The interface implemented by multi-factor authentication shared parameter
 * generators.
 */
interface MfaSharedParametersGeneratorInterface
{
    /**
     * Generate a set of multi-factor authentication shared parameters.
     *
     * @param MfaConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return MfaSharedParametersInterface The generated shared parameters.
     */
    public function generate(MfaConfigurationInterface $configuration);
}

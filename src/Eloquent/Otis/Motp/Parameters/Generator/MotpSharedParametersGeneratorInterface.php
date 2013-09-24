<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Parameters\Generator;

use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;
use Eloquent\Otis\Motp\Parameters\MotpSharedParametersInterface;

/**
 * The interface implemented by mOTP shared parameter generators.
 */
interface MotpSharedParametersGeneratorInterface
{
    /**
     * Generate a set of mOTP shared parameters.
     *
     * @param MotpConfigurationInterface $configuration The configuration to generate shared parameters for.
     *
     * @return MotpSharedParametersInterface The generated shared parameters.
     */
    public function generateMotp(MotpConfigurationInterface $configuration);
}

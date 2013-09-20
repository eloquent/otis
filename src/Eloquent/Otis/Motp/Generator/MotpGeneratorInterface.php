<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Motp\Generator;

use Eloquent\Otis\Motp\Configuration\MotpConfigurationInterface;
use Eloquent\Otis\Motp\Parameters\MotpSharedParametersInterface;

/**
 * The interface implemented by mOTP generators.
 */
interface MotpGeneratorInterface
{
    /**
     * Generate an mOTP value.
     *
     * @link http://motp.sourceforge.net/#1.1
     *
     * @param MotpConfigurationInterface    $configuration The configuration to use for generation.
     * @param MotpSharedParametersInterface $shared        The shared parameters to use for generation.
     *
     * @return string The generated mOTP value.
     */
    public function generateMotp(
        MotpConfigurationInterface $configuration,
        MotpSharedParametersInterface $shared
    );
}

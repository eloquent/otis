<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration\Preset;

use Eloquent\Otis\Configuration\MfaConfigurationInterface;

/**
 * The interface implemented by multi-factor authentication configuration
 * presets.
 */
interface MfaConfigurationPresetInterface
{
    /**
     * Get the preset key.
     *
     * @return string The key.
     */
    public function key();

    /**
     * Get the preset configuration.
     *
     * @return MfaConfigurationInterface The configuration.
     */
    public function configuration();

    /**
     * Get the preset description.
     *
     * @return string|null The description, if available.
     */
    public function description();
}

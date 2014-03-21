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
 * Represents a multi-factor authentication configuration preset.
 */
class MfaConfigurationPreset implements MfaConfigurationPresetInterface
{
    /**
     * Construct a new multi-factor authentication configuration preset.
     *
     * @param string                    $key           The key.
     * @param MfaConfigurationInterface $configuration The configuration.
     * @param string|null               $description   The description.
     */
    public function __construct(
        $key,
        MfaConfigurationInterface $configuration,
        $description = null
    ) {
        $this->key = $key;
        $this->configuration = $configuration;
        $this->description = $description;
    }

    /**
     * Get the preset key.
     *
     * @return string The key.
     */
    public function key()
    {
        return $this->key;
    }

    /**
     * Get the preset configuration.
     *
     * @return MfaConfigurationInterface The configuration.
     */
    public function configuration()
    {
        return $this->configuration;
    }

    /**
     * Get the preset description.
     *
     * @return string|null The description, if available.
     */
    public function description()
    {
        return $this->description;
    }

    private $key;
    private $configuration;
    private $description;
}

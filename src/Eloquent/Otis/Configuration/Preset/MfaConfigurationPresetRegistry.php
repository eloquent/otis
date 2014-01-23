<?php

/*
 * This file is part of the Otis package.
 *
 * Copyright © 2013 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eloquent\Otis\Configuration\Preset;

/**
 * A registry for multi-factor authentication configuration presets.
 */
class MfaConfigurationPresetRegistry implements
    MfaConfigurationPresetRegistryInterface
{
    /**
     * Construct a new multi-factor authentication configuration preset
     * registry.
     *
     * @param array<MfaConfigurationPresetInterface>|null $presets The configuration presets.
     */
    public function __construct(array $presets = null)
    {
        if (null === $presets) {
            $presets = array();
        }

        $this->setPresets($presets);
    }

    /**
     * Removes all configuration presets from this registry and replaces them
     * with the supplied presets.
     *
     * @param array<MfaConfigurationPresetInterface> $presets The configuration presets.
     */
    public function setPresets(array $presets)
    {
        $this->presets = array();
        $this->addPresets($presets);
    }

    /**
     * Adds the suppied configuration presets to this registry.
     *
     * @param array<MfaConfigurationPresetInterface> $presets The configuration presets.
     */
    public function addPresets(array $presets)
    {
        array_map(array($this, 'add'), $presets);
    }

    /**
     * Removes all configuration presets from this registry.
     */
    public function clear()
    {
        $this->setPresets(array());
    }

    /**
     * Get all of the registered presets.
     *
     * @return array<MfaConfigurationPresetInterface> The registered presets.
     */
    public function presets()
    {
        return $this->presets;
    }

    /**
     * Adds a configuration preset to this registry.
     *
     * If a preset exists with the same key, it will be replaced.
     *
     * @param MfaConfigurationPresetInterface $preset The preset to add.
     */
    public function add(MfaConfigurationPresetInterface $preset)
    {
        $this->presets[$preset->key()] = $preset;
    }

    /**
     * Removes a configuration preset from the registry.
     *
     * @param string $key The preset key to search for.
     *
     * @return boolean True if an associated preset was found.
     */
    public function remove($key)
    {
        $found = $this->has($key);
        unset($this->presets[$key]);

        return $found;
    }

    /**
     * Returns true if a registered preset exists for the supplied key.
     *
     * @param string $key The preset key to search for.
     *
     * @return boolean True if an associated preset is found.
     */
    public function has($key)
    {
        return array_key_exists($key, $this->presets);
    }

    /**
     * Gets a configuration preset by key.
     *
     * @param string $key The preset key to search for.
     *
     * @return MfaConfigurationPresetInterface                    The associated configuration preset.
     * @throws Exception\UndefinedMfaConfigurationPresetException If no associated preset is found.
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new Exception\UndefinedMfaConfigurationPresetException($key);
        }

        return $this->presets[$key];
    }

    private $presets;
}

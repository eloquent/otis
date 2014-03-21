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

/**
 * The interface implemented by multi-factor authentication configuration
 * preset registries.
 */
interface MfaConfigurationPresetRegistryInterface
{
    /**
     * Removes all configuration presets from this registry and replaces them
     * with the supplied presets.
     *
     * @param array<MfaConfigurationPresetInterface> $presets The configuration presets.
     */
    public function setPresets(array $presets);

    /**
     * Adds the suppied configuration presets to this registry.
     *
     * @param array<MfaConfigurationPresetInterface> $presets The configuration presets.
     */
    public function addPresets(array $presets);

    /**
     * Removes all configuration presets from this registry.
     */
    public function clear();

    /**
     * Get all of the registered presets.
     *
     * @return array<MfaConfigurationPresetInterface> The registered presets.
     */
    public function presets();

    /**
     * Adds a configuration preset to this registry.
     *
     * If a preset exists with the same key, it will be replaced.
     *
     * @param MfaConfigurationPresetInterface $preset The preset to add.
     */
    public function add(MfaConfigurationPresetInterface $preset);

    /**
     * Removes a configuration preset from the registry.
     *
     * @param string $key The preset key to search for.
     *
     * @return boolean True if an associated preset was found.
     */
    public function remove($key);

    /**
     * Returns true if a registered preset exists for the supplied key.
     *
     * @param string $key The preset key to search for.
     *
     * @return boolean True if an associated preset is found.
     */
    public function has($key);

    /**
     * Gets a configuration preset by key.
     *
     * @param string $key The preset key to search for.
     *
     * @return MfaConfigurationPresetInterface                    The associated configuration preset.
     * @throws Exception\UndefinedMfaConfigurationPresetException If no associated preset is found.
     */
    public function get($key);
}

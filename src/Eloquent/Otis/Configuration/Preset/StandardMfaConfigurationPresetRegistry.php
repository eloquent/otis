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

use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Motp\Configuration\MotpConfiguration;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;

/**
 * A multi-factor authentication configuration preset registry, with some
 * pre-configured presets.
 */
class StandardMfaConfigurationPresetRegistry extends
    MfaConfigurationPresetRegistry
{
    /**
     * Construct a new standard multi-factor authentication configuration preset
     * registry.
     *
     * @param array<MfaConfigurationPresetInterface>|null $presets Additional configuration presets to add.
     */
    public function __construct(array $presets = null)
    {
        if (null === $presets) {
            $presets = array();
        }

        parent::__construct(
            array(
                new MfaConfigurationPreset(
                    'totp',
                    new TotpConfiguration,
                    'Standard TOTP'
                ),
                new MfaConfigurationPreset(
                    'hotp',
                    new HotpConfiguration,
                    'Standard HOTP'
                ),
                new MfaConfigurationPreset(
                    'motp',
                    new MotpConfiguration,
                    'Standard mOTP'
                ),
            )
        );

        $this->addPresets($presets);
    }
}

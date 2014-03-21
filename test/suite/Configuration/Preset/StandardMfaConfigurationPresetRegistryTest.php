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
use PHPUnit_Framework_TestCase;

class StandardMfaConfigurationPresetRegistryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new TotpConfiguration;
        $this->presetA = new MfaConfigurationPreset('keyA', $this->configuration);
        $this->presetB = new MfaConfigurationPreset('keyB', $this->configuration);

        $this->registry = new StandardMfaConfigurationPresetRegistry(array($this->presetA, $this->presetB));

        $this->expectedTotpPreset = new MfaConfigurationPreset('totp', new TotpConfiguration, 'Standard TOTP');
        $this->expectedHotpPreset = new MfaConfigurationPreset('hotp', new HotpConfiguration, 'Standard HOTP');
        $this->expectedMotpPreset = new MfaConfigurationPreset('motp', new MotpConfiguration, 'Standard mOTP');
    }

    public function testConstructor()
    {
        $this->assertEquals(
            array(
                'totp' => $this->expectedTotpPreset,
                'hotp' => $this->expectedHotpPreset,
                'motp' => $this->expectedMotpPreset,
                'keyA' => $this->presetA,
                'keyB' => $this->presetB,
            ),
            $this->registry->presets()
        );
    }

    public function testConstructorDefaults()
    {
        $this->registry = new StandardMfaConfigurationPresetRegistry;

        $this->assertEquals(
            array(
                'totp' => $this->expectedTotpPreset,
                'hotp' => $this->expectedHotpPreset,
                'motp' => $this->expectedMotpPreset,
            ),
            $this->registry->presets()
        );
    }
}

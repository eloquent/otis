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

use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use PHPUnit_Framework_TestCase;

class MfaConfigurationPresetRegistryTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new TotpConfiguration;
        $this->presetA = new MfaConfigurationPreset('keyA', $this->configuration);
        $this->presetB = new MfaConfigurationPreset('keyB', $this->configuration);
        $this->presetC = new MfaConfigurationPreset('keyC', $this->configuration);
        $this->presetD = new MfaConfigurationPreset('keyD', $this->configuration);

        $this->registry = new MfaConfigurationPresetRegistry(array($this->presetA, $this->presetB));
    }

    public function testConstructor()
    {
        $this->assertSame(array('keyA' => $this->presetA, 'keyB' => $this->presetB), $this->registry->presets());
    }

    public function testConstructorDefaults()
    {
        $this->registry = new MfaConfigurationPresetRegistry;

        $this->assertSame(array(), $this->registry->presets());
    }

    public function testSetPresets()
    {
        $this->registry->setPresets(array($this->presetC, $this->presetD));

        $this->assertSame(array('keyC' => $this->presetC, 'keyD' => $this->presetD), $this->registry->presets());
    }

    public function testAddPresets()
    {
        $this->registry->addPresets(array($this->presetC, $this->presetD));

        $this->assertSame(
            array(
                'keyA' => $this->presetA,
                'keyB' => $this->presetB,
                'keyC' => $this->presetC,
                'keyD' => $this->presetD,
            ),
            $this->registry->presets()
        );
    }

    public function testClear()
    {
        $this->registry->clear();

        $this->assertSame(array(), $this->registry->presets());
    }

    public function testAdd()
    {
        $preset = new MfaConfigurationPreset('keyA', $this->configuration);
        $this->registry->add($preset);
        $this->registry->add($this->presetC);

        $this->assertSame(
            array(
                'keyA' => $preset,
                'keyB' => $this->presetB,
                'keyC' => $this->presetC,
            ),
            $this->registry->presets()
        );
    }

    public function testRemove()
    {
        $this->assertTrue($this->registry->remove('keyA'));
        $this->assertSame(array('keyB' => $this->presetB), $this->registry->presets());
        $this->assertFalse($this->registry->remove('keyA'));
        $this->assertSame(array('keyB' => $this->presetB), $this->registry->presets());
    }

    public function testHas()
    {
        $this->assertTrue($this->registry->has('keyA'));
        $this->assertFalse($this->registry->has('keyC'));
    }

    public function testGet()
    {
        $this->assertSame($this->presetA, $this->registry->get('keyA'));
        $this->assertSame($this->presetB, $this->registry->get('keyB'));
    }

    public function testGetFailure()
    {
        $this->setExpectedException(__NAMESPACE__ . '\Exception\UndefinedMfaConfigurationPresetException');

        $this->registry->get('keyC');
    }
}

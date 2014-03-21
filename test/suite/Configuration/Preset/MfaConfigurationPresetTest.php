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

use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use PHPUnit_Framework_TestCase;

class MfaConfigurationPresetTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->configuration = new TotpConfiguration;
        $this->preset = new MfaConfigurationPreset('key', $this->configuration, 'description');
    }

    public function testConstructor()
    {
        $this->assertSame('key', $this->preset->key());
        $this->assertSame($this->configuration, $this->preset->configuration());
        $this->assertSame('description', $this->preset->description());
    }

    public function testConstructorDefaults()
    {
        $this->preset = new MfaConfigurationPreset('key', $this->configuration);

        $this->assertNull($this->preset->description());
    }
}

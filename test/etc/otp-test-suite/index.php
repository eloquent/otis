<?php

use Base32\Base32;
use Eloquent\Asplode\Asplode;
use Eloquent\Otis\Driver\MfaDriverFactory;
use Eloquent\Otis\Hotp\Configuration\HotpConfiguration;
use Eloquent\Otis\Hotp\HotpHashAlgorithm;
use Eloquent\Otis\Hotp\Value\HotpValueGenerator;
use Eloquent\Otis\Parameters\CounterBasedOtpSharedParameters;
use Eloquent\Otis\Parameters\TimeBasedOtpSharedParameters;
use Eloquent\Otis\Totp\Configuration\TotpConfiguration;
use Eloquent\Otis\Totp\Value\TotpValueGenerator;
use Eloquent\Otis\Uri\QrCode\GoogleChartsQrCodeUriFactory;

require __DIR__ . '/../../../vendor/autoload.php';

Asplode::instance()->install();

$driverFactory = new MfaDriverFactory;
$qrCodeUriFactory = new GoogleChartsQrCodeUriFactory;

$secret = '1234567890';
$time = time();

$entries = array(
    array('label' => 'TOTP, 30 seconds, 6-digit, SHA-1',   'configuration' => new TotpConfiguration(6, 30, null, null, null, HotpHashAlgorithm::SHA1())),
    array('label' => 'TOTP, 30 seconds, 8-digit, SHA-1',   'configuration' => new TotpConfiguration(8, 30, null, null, null, HotpHashAlgorithm::SHA1())),
    array('label' => 'TOTP, 30 seconds, 10-digit, SHA-1',  'configuration' => new TotpConfiguration(10, 30, null, null, null, HotpHashAlgorithm::SHA1())),
    array('label' => 'TOTP, 30 seconds, 6-digit, SHA-256', 'configuration' => new TotpConfiguration(6, 30, null, null, null, HotpHashAlgorithm::SHA256())),
    array('label' => 'TOTP, 30 seconds, 6-digit, SHA-512', 'configuration' => new TotpConfiguration(6, 30, null, null, null, HotpHashAlgorithm::SHA512())),
    array('label' => 'TOTP, 60 seconds, 6-digit, SHA-1',   'configuration' => new TotpConfiguration(6, 60, null, null, null, HotpHashAlgorithm::SHA1())),

    array('label' => 'HOTP, 6-digit, SHA-1',   'configuration' => new HotpConfiguration(6, null, null, null, HotpHashAlgorithm::SHA1())),
    array('label' => 'HOTP, 8-digit, SHA-1',   'configuration' => new HotpConfiguration(8, null, null, null, HotpHashAlgorithm::SHA1())),
    array('label' => 'HOTP, 10-digit, SHA-1',  'configuration' => new HotpConfiguration(10, null, null, null, HotpHashAlgorithm::SHA1())),
    array('label' => 'HOTP, 6-digit, SHA-256', 'configuration' => new HotpConfiguration(6, null, null, null, HotpHashAlgorithm::SHA256())),
    array('label' => 'HOTP, 6-digit, SHA-512', 'configuration' => new HotpConfiguration(6, null, null, null, HotpHashAlgorithm::SHA512())),
);

foreach ($entries as $index => $entry) {
    if ($entry['configuration'] instanceof TotpConfiguration) {
        $shared = new TimeBasedOtpSharedParameters($secret, $time);

        $generator = new TotpValueGenerator;
        $entries[$index]['values'] = array();
        for ($i = 0; $i < 6; ++$i) {
            $thisTime = $shared->time() +
                ($i * $entry['configuration']->window());
            $currentShared = clone $shared;
            $currentShared->setTime($thisTime);

            $entries[$index]['values'][$thisTime] = $generator
                ->generate($entry['configuration'], $currentShared)
                ->string($entry['configuration']->digits());
        }
    } else {
        $shared = new CounterBasedOtpSharedParameters(
            $secret,
            $entry['configuration']->initialCounter()
        );

        $generator = new HotpValueGenerator;
        $entries[$index]['values'] = array();
        for ($i = 0; $i < 6; ++$i) {
            $currentShared = clone $shared;
            $currentShared->setCounter($shared->counter() + $i);

            $entries[$index]['values'][] = $generator
                ->generate($entry['configuration'], $currentShared)
                ->string($entry['configuration']->digits());
        }
    }

    $driver = $driverFactory->create($entry['configuration']);

    $entries[$index]['label'] = ($index + 1) . '. ' . $entry['label'];
    $entries[$index]['uri'] = $driver->initializationUriFactory()->create(
        $entry['configuration'],
        $shared,
        $entries[$index]['label'],
        'Eloquent Software'
    );
    $entries[$index]['qr-code-uri'] = $qrCodeUriFactory->createUri(
        $entries[$index]['uri']
    );
}

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Otis OTP feature test suite</title>

        <link href="https://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
        <style type="text/css">
            body {
                padding: 30px;
            }
            .row {
                margin-top: 60px;
                padding-top: 20px;
                border-top: 1px #ccc solid;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>Otis OTP feature test suite</h1>
            <p>
                Use these QR codes to test the features of a particular OTP app. If the app does not support QR
                code input, the secret is provided for manual entry. In some cases, secrets may need to be entered
                in Base32 encoding.
            </p>
            <p>
                All QR codes use 'Eloquent Software' as the issuer. If the OTP app shows 'Eloquent Software:' at the
                beginning of the label, this means it does not support legacy-style issuer conventions.
            </p>
            <p>
                Simply reload this page to get newer values for TOTP passwords. Keep in mind that for some HOTP
                implementations, the counter starts at 1, wheras others start at 0.
            </p>

            <?php $i = 0; foreach ($entries as $entry): ?>
                <div class="row">
                    <div class="span8">
                        <h2><?php echo htmlspecialchars($entry['label']) ?></h2>
                        <dl>
                            <dt>Expected values</dt>
                            <dd>
                                <?php if ($entry['configuration'] instanceof TotpConfiguration): ?>
                                    <ul>
                                        <?php foreach ($entry['values'] as $thisTime => $value): ?>
                                            <li>
                                                <time datetime="<?php echo htmlspecialchars(date('c', $thisTime)) ?>" title="<?php echo htmlspecialchars(date('c', $thisTime)) ?>"><?php echo htmlspecialchars(date('H:i:s', $thisTime)) ?></time>
                                                -
                                                <strong><?php echo htmlspecialchars($value) ?></strong>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <ol start="0">
                                        <?php foreach ($entry['values'] as $value): ?>
                                            <li><strong><?php echo htmlspecialchars($value) ?></strong></li>
                                        <?php endforeach; ?>
                                    </ol>
                                <?php endif; ?>
                            </dd>

                            <dt>Secret</dt>
                            <dd><?php echo htmlspecialchars($secret) ?></dd>

                            <dt>Secret (Base32)</dt>
                            <dd><?php echo htmlspecialchars(Base32::encode($secret)) ?></dd>

                            <dt>Raw URI</dt>
                            <dd><a href="<?php echo htmlspecialchars($entry['uri']) ?>"><?php echo htmlspecialchars($entry['uri']) ?></a></dd>
                        </dl>
                    </div>
                    <div class="span4">
                        <a href="<?php echo htmlspecialchars($entry['qr-code-uri']) ?>"><img src="<?php echo htmlspecialchars($entry['qr-code-uri']) ?>" /></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>

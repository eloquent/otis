<?php

use Base32\Base32;
use Eloquent\Asplode\Asplode;
use Eloquent\Otis\GoogleAuthenticator\GoogleAuthenticatorUriFactory;
use Eloquent\Otis\Hotp\HotpGenerator;
use Eloquent\Otis\QrCode\GoogleChartsQrCodeUriFactory;
use Eloquent\Otis\Totp\TotpGenerator;

require __DIR__ . '/../../../vendor/autoload.php';

Asplode::instance()->install();

$uriFactory = new GoogleAuthenticatorUriFactory;
$qrCodeUriFactory = new GoogleChartsQrCodeUriFactory;

$secret = '1234567890';
$time = time();

$entries = array(
    array('label' => 'TOTP, 30 seconds, 6-digit, SHA-1',   'type' => 'totp', 'secret' => $secret, 'window' => 30, 'digits' => 6,  'algorithm' => 'SHA1'),
    array('label' => 'TOTP, 30 seconds, 8-digit, SHA-1',   'type' => 'totp', 'secret' => $secret, 'window' => 30, 'digits' => 8,  'algorithm' => 'SHA1'),
    array('label' => 'TOTP, 30 seconds, 10-digit, SHA-1',  'type' => 'totp', 'secret' => $secret, 'window' => 30, 'digits' => 10, 'algorithm' => 'SHA1'),
    array('label' => 'TOTP, 30 seconds, 6-digit, SHA-256', 'type' => 'totp', 'secret' => $secret, 'window' => 30, 'digits' => 6,  'algorithm' => 'SHA256'),
    array('label' => 'TOTP, 30 seconds, 6-digit, SHA-512', 'type' => 'totp', 'secret' => $secret, 'window' => 30, 'digits' => 6,  'algorithm' => 'SHA512'),
    array('label' => 'TOTP, 60 seconds, 6-digit, SHA-1',   'type' => 'totp', 'secret' => $secret, 'window' => 60, 'digits' => 6,  'algorithm' => 'SHA1'),

    array('label' => 'HOTP, 6-digit, SHA-1',   'type' => 'hotp', 'secret' => $secret, 'digits' => 6,  'algorithm' => 'SHA1'),
    array('label' => 'HOTP, 8-digit, SHA-1',   'type' => 'hotp', 'secret' => $secret, 'digits' => 8,  'algorithm' => 'SHA1'),
    array('label' => 'HOTP, 10-digit, SHA-1',  'type' => 'hotp', 'secret' => $secret, 'digits' => 10, 'algorithm' => 'SHA1'),
    array('label' => 'HOTP, 6-digit, SHA-256', 'type' => 'hotp', 'secret' => $secret, 'digits' => 6,  'algorithm' => 'SHA256'),
    array('label' => 'HOTP, 6-digit, SHA-512', 'type' => 'hotp', 'secret' => $secret, 'digits' => 6,  'algorithm' => 'SHA512'),
);

foreach ($entries as $index => $entry) {
    $entries[$index]['label'] = ($index + 1) . '. ' . $entry['label'];

    if ('totp' === $entry['type']) {
        $entries[$index]['uri'] = $uriFactory->createTotpUri(
            $entry['secret'],
            $entries[$index]['label'],
            'Eloquent Software',
            $entry['window'],
            $entry['digits'],
            $entry['algorithm'],
            true
        );

        $generator = new TotpGenerator(new HotpGenerator($entry['algorithm']));
        $entries[$index]['values'] = array();
        for ($i = 0; $i < 6; ++$i) {
            $thisTime = $time + ($i * $entry['window']);
            $entries[$index]['values'][$thisTime] = $generator
                ->generate($secret, $entry['window'], $thisTime)
                ->string($entry['digits']);
        }
    } else {
        $entries[$index]['uri'] = $uriFactory->createHotpUri(
            $entry['secret'],
            $entries[$index]['label'],
            'Eloquent Software',
            null,
            $entry['digits'],
            $entry['algorithm'],
            true
        );

        $generator = new HotpGenerator($entry['algorithm']);
        $entries[$index]['values'] = array();
        for ($i = 0; $i < 6; ++$i) {
            $entries[$index]['values'][] = $generator
                ->generate($secret, $i)
                ->string($entry['digits']);
        }
    }

    $entries[$index]['qr-code-uri'] = $qrCodeUriFactory->createUri($entries[$index]['uri']);
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
                                <?php if ('totp' === $entry['type']): ?>
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
                            <dd><?php echo htmlspecialchars($entry['secret']) ?></dd>

                            <dt>Secret (Base32)</dt>
                            <dd><?php echo htmlspecialchars(Base32::encode($entry['secret'])) ?></dd>

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

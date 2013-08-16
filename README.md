# Otis

*One-time password / two-factor authentication library for PHP.*

[![Build Status]][Latest build]
[![Test Coverage]][Test coverage report]
[![Uses Semantic Versioning]][SemVer]

## Installation and documentation

* Available as [Composer] package [eloquent/otis].
* [API documentation] available.

## What is *Otis*?

*Otis* is a PHP library for implementing [one-time password] / [two-factor
authentication] systems. *Otis* provides generators and validators for both
[TOTP][] (time-based passwords as defined in [RFC 6238]) and [HOTP][]
(counter-based passwords as covered in [RFC 4226]). *Otis* supports all hashing
algorithms (SHA-1, SHA-256, SHA-512).

In addition, *Otis* provides tools for generating the URI format understood by
[Google Authenticator] and other compatible OTP apps, as well as URIs for QR
code generation services to further ease integration.

## Usage

### Validating a TOTP password

```php
use Eloquent\Otis\Totp\TotpValidator;

$validator = new TotpValidator;

$password = '<OTP password>'; // the password to validate
$secret = '<OTP secret>';     // the shared secret

$result = $validator->validate($password, $secret);
```

### Validating an HOTP password

```php
use Eloquent\Otis\Hotp\HotpValidator;

$validator = new HotpValidator;

$password = '<OTP password>'; // the password to validate
$secret = '<OTP secret>';     // the shared secret
$counter = 0;                 // current counter value

$result = $validator->validate($password, $secret, $counter, $newCounter);
if ($result) {
    $counter = $newCounter;
}
```

### Generating a Google Authenticator URI

```php
use Eloquent\Otis\GoogleAuthenticator\GoogleAuthenticatorUriFactory;

$uriFactory = new GoogleAuthenticatorUriFactory;

$uri = $uriFactory->createTotpUri('12345678901234567890', 'test.ease@example.org');
echo $uri; // outputs 'otpauth://totp/test.ease%40example.org?secret=GEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'
```

### Generating a Google Authenticator QR code URI

```php
use Eloquent\Otis\GoogleAuthenticator\GoogleAuthenticatorUriFactory;
use Eloquent\Otis\QrCode\GoogleChartsQrCodeUriFactory;

$uriFactory = new GoogleAuthenticatorUriFactory;
$qrCodeUriFactory = new GoogleChartsQrCodeUriFactory;

$qrCodeUri = $qrCodeUriFactory->createUri(
    $uriFactory->createTotpUri('12345678901234567890', 'test.ease@example.org')
);
echo $qrCodeUri; // outputs 'https://chart.googleapis.com/chart?cht=qr&chs=250x250&chld=%7C0&chl=otpauth%3A%2F%2Ftotp%2Ftest.ease%2540example.org%3Fsecret%3DGEZDGNBVGY3TQOJQGEZDGNBVGY3TQOJQ'
```

### Validating a sequence of HOTP passwords

```php
use Eloquent\Otis\Hotp\HotpValidator;

$validator = new HotpValidator;

// the password sequence to validate
$passwords = array('<OTP password 1>', '<OTP password 2>', '<OTP password 3>');
$secret = '<OTP secret>';      // the shared secret
$counter = 0;                  // current counter value

$result = $validator->validateSequence($passwords, $secret, $counter, $newCounter);
if ($result) {
    $counter = $newCounter;
}
```

## Security considerations

When implementing an OTP system, the following points should be considered with
care:

- Each password should only be considered valid once. This helps to avoid replay
  attacks. This is especially important for time-based passwords that may
  otherwise be considered valid for an entire time period. Keeping track of
  which one-time passwords have already been used in a successful validation is
  the only way to ensure a password is not re-used.
- The shared secret should be treated as sensitive information. When storing the
  secret on the server side, strong two-way encryption should be used.
- In order for time-based OTP systems to work well, there should be minimal
  differences in the system time of the server, and the OTP device in use.
  *Otis* defaults allow -1 to +1 time windows (a window is usually 30 seconds),
  but the validator can be configured to accept passwords from larger time
  windows.

## Try *Otis*

*Otis* has a simple demonstration system. In order to run the demos, these
instructions must be followed:

- [Install Google Authenticator] or a compatible OTP app
- Clone the *Otis* repository
- Install [Composer] dependencies, including dev dependencies
- Run `test/bin/totp` or `test/bin/hotp` depending on which type of OTP system
  is preferred
- A link to a QR code image will be launched in the default browser
- Scan this QR code with the OTP app
- Return to the console and enter the passwords provided by the OTP app

In addition, there is a test suite for determining the capabilities of OTP apps.
In order to run the test suite follow these steps:

- Install [Composer] dependencies as above
- Change directory into `test/etc/otp-test-suite`
- Run `php -S localhost:8000`
- Visit http://localhost:8000/ in a browser

## OTP app capabilities

Not all OTP apps support the same features. Even [Google Authenticator] does not
support all the features that its [URI format] is capable of expressing (and
support varies across platforms).

This table is an attempt to cover some of the most popular available OTP apps,
and their features, and is accurate at the time of writing (2013-08-16).

To contribute more data to this table, use the OTP test suite as described in
the [Try Otis](#try-otis) section.

<table>
    <thead>
        <tr><th>Name</th>                                                                                                                                       <th>Platform</th>     <th>Version</th>  <th>QR</th><th>TOTP</th><th>HOTP</th><th>6Digit</th><th>8Digit</th><th>10Digit</th><th>SHA1</th><th>SHA256</th><th>SHA512</th><th>BigWindow</th><th>Issuer</th><th>LegacyIssuer</th></tr>
    </thead>
    <tbody>
        <tr><td><a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">Google Authenticator</a></td>                    <td>Android</td>      <td>2.49</td>     <td>✓</td> <td>✓</td>   <td>✓</td>   <td>✓</td>     <td>✗</td>     <td>✗</td>      <td>✓</td>   <td>✗</td>     <td>✗</td>     <td>✗</td>        <td>✓</td>     <td>✓</td></tr>
        <tr><td><a href="https://play.google.com/store/apps/details?id=uk.co.bitethebullet.android.token">Android Token</a></td>                                <td>Android</td>      <td>2.02</td>     <td>✓</td> <td>✓</td>   <td>✓</td>   <td>✓</td>     <td>✓</td>     <td>✗</td>      <td>✓</td>   <td>✗</td>     <td>✗</td>     <td>✓</td>        <td>✗</td>     <td>✗</td></tr>
        <tr><td><a href="https://play.google.com/store/apps/details?id=org.cry.otp">Mobile-OTP</a></td>                                                         <td>Android</td>      <td>1.5</td>      <td>✗</td> <td>✓</td>   <td>✓</td>   <td>✓</td>     <td>✓</td>     <td>✗</td>      <td>✓</td>   <td>✓</td>     <td>✓</td>     <td>✓</td>        <td>✗</td>     <td>✗</td></tr>
        <tr><td><a href="https://itunes.apple.com/au/app/google-authenticator/id388497605">Google Authenticator</a></td>                                        <td>iOS</td>          <td>1.1.4.755</td><td>✓</td> <td>✓</td>   <td>✗</td>   <td>✓</td>     <td>✓</td>     <td>✗</td>      <td>✓</td>   <td>✓</td>     <td>✓</td>     <td>✓</td>        <td>✗</td>     <td>✗</td></tr>
        <tr><td><a href="https://itunes.apple.com/us/app/otp-auth/id659877384">OTP Auth</a></td>                                                                <td>iOS</td>          <td>1.0.3</td>    <td>✓</td> <td>✓</td>   <td>✓</td>   <td>✓</td>     <td>✓</td>     <td>✗</td>      <td>✓</td>   <td>✓</td>     <td>✓</td>     <td>✓</td>        <td>✗</td>     <td>✗</td></tr>
        <tr><td><a href="https://itunes.apple.com/us/app/hde-otp-generator/id571240327">HDE OTP Generator</a></td>                                              <td>iOS</td>          <td>1.2.0</td>    <td>✓</td> <td>✓</td>   <td>✗</td>   <td>✓</td>     <td>✗</td>     <td>✗</td>      <td>✓</td>   <td>✗</td>     <td>✗</td>     <td>✗</td>        <td>✗</td>     <td>✗</td></tr>
        <tr><td>Google Authenticator</td>                                                                                                                       <td>BlackBerry</td>   <td>1.1.1</td>    <td>✗</td> <td>✓</td>   <td>✓</td>   <td>✓</td>     <td>✗</td>     <td>✗</td>      <td>✓</td>   <td>✗</td>     <td>✗</td>     <td>✗</td>        <td>✗</td>     <td>✗</td></tr>
        <tr><td><a href="http://appworld.blackberry.com/webstore/content/22517879/">Authomator</a></td>                                                         <td>BlackBerry</td>   <td>1.1.0</td>    <td>?</td> <td>?</td>   <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>      <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>        <td>?</td>     <td>?</td></tr>
        <tr><td><a href="http://appworld.blackberry.com/webstore/content/29401059/">2 Steps Authenticator</a></td>                                              <td>BlackBerry</td>   <td>10.1.0</td>   <td>?</td> <td>?</td>   <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>      <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>        <td>?</td>     <td>?</td></tr>
        <tr><td><a href="http://www.windowsphone.com/en-us/store/app/authenticator/021dd79f-0598-e011-986b-78e7d1fa76f8">Authenticator (slugonamission)</a></td><td>Windows Phone</td><td>1.2.0.0</td>  <td>?</td> <td>?</td>   <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>      <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>        <td>?</td>     <td>?</td></tr>
        <tr><td><a href="http://www.windowsphone.com/en-us/store/app/authenticator/82c12390-0176-43de-916e-5613d17f61a0">Authenticator (Matt McCormick)</a></td><td>Windows Phone</td><td>1.5.0.0</td>  <td>?</td> <td>?</td>   <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>      <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>        <td>?</td>     <td>?</td></tr>
        <tr><td><a href="http://www.windowsphone.com/en-us/store/app/authenticator/f758eb53-ff04-404b-9382-4d4e26f7bd46">Authenticator+</a></td>                <td>Windows Phone</td><td>1.6.1.1</td>  <td>?</td> <td>?</td>   <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>      <td>?</td>   <td>?</td>     <td>?</td>     <td>?</td>        <td>?</td>     <td>?</td></tr>
    </tbody>
</table>

### Capability legend

- **QR** - support for [Google Authenticator] compatible QR codes
- **TOTP** - support for [TOTP] passwords
- **HOTP** - support for [HOTP] passwords
- **6Digit** - support for 6 digit passwords
- **8Digit** - support for 8 digit passwords
- **10Digit** - support for 10 digit passwords
- **SHA1** - support for SHA-1-based passwords
- **SHA256** - support for SHA-256-based passwords
- **SHA512** - support for SHA-512-based passwords
- **BigWindow** - support for alternative TOTP window sizes
- **Issuer** - support for Issuer names
- **LegacyIssuer** - legacy support for Issuer names

<!-- References -->

[API documentation]: http://lqnt.co/otis/artifacts/documentation/api/
[Composer]: http://getcomposer.org/
[eloquent/otis]: https://packagist.org/packages/eloquent/otis
[Google Authenticator]: http://en.wikipedia.org/wiki/Google_Authenticator
[HOTP]: http://en.wikipedia.org/wiki/HMAC-based_One-time_Password_Algorithm
[Install Google Authenticator]: https://support.google.com/accounts/answer/1066447?hl=en
[one-time password]: http://en.wikipedia.org/wiki/One-time_password
[RFC 4226]: http://tools.ietf.org/html/rfc4226
[RFC 6238]: http://tools.ietf.org/html/rfc6238
[TOTP]: http://en.wikipedia.org/wiki/Time-based_One-time_Password_Algorithm
[two-factor authentication]: http://en.wikipedia.org/wiki/Multi-factor_authentication
[URI format]: https://code.google.com/p/google-authenticator/wiki/KeyUriFormat

[Build Status]: https://api.travis-ci.org/eloquent/otis.png?branch=master
[Latest build]: https://travis-ci.org/eloquent/otis
[SemVer]: http://semver.org/
[Test coverage report]: https://coveralls.io/r/eloquent/otis
[Test Coverage]: https://coveralls.io/repos/eloquent/otis/badge.png?branch=master
[Uses Semantic Versioning]: http://b.repl.ca/v1/semver-yes-brightgreen.png

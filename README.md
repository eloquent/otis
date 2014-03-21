# Otis

*One-time password / multi-factor authentication library for PHP.*

[![The most recent stable version is 0.3.0][version-image]][Semantic versioning]
[![Current build status image][build-image]][Current build status]
[![Current coverage status image][coverage-image]][Current coverage status]

## Installation and documentation

* Available as [Composer] package [eloquent/otis].
* [API documentation] available.

## What is *Otis*?

*Otis* is a PHP library for implementing [one-time password] / [multi-factor
authentication] systems. *Otis* provides generators and validators for both
[TOTP][] (time-based passwords as defined in [RFC 6238]) and [HOTP][]
(counter-based passwords as covered in [RFC 4226]). *Otis* supports all hashing
algorithms (SHA-1, SHA-256, SHA-512).

In addition, *Otis* provides tools for generating the [URI format] understood by
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
  secret on the server side, strong two-way encryption should be used. A
  solution such as [Lockbox] would be ideal.
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
- Visit [localhost:8000](http://localhost:8000/) in a browser

## OTP app capabilities

Not all OTP apps support the same features. Even [Google Authenticator] does not
support all the features that its [URI format] is capable of expressing (and
support varies across platforms).

For a table of OTP apps and their capabilities, see [OTP app capabilities] in
the wiki.

<!-- References -->

[API documentation]: http://lqnt.co/otis/artifacts/documentation/api/
[Google Authenticator]: http://en.wikipedia.org/wiki/Google_Authenticator
[HOTP]: http://en.wikipedia.org/wiki/HMAC-based_One-time_Password_Algorithm
[Install Google Authenticator]: https://support.google.com/accounts/answer/1066447?hl=en
[Lockbox]: http://lqnt.co/lockbox
[one-time password]: http://en.wikipedia.org/wiki/One-time_password
[OTP app capabilities]: https://github.com/eloquent/otis/wiki/otp-app-capabilities
[RFC 4226]: http://tools.ietf.org/html/rfc4226
[RFC 6238]: http://tools.ietf.org/html/rfc6238
[TOTP]: http://en.wikipedia.org/wiki/Time-based_One-time_Password_Algorithm
[multi-factor authentication]: http://en.wikipedia.org/wiki/Multi-factor_authentication
[URI format]: https://code.google.com/p/google-authenticator/wiki/KeyUriFormat

[API documentation]: http://lqnt.co/otis/artifacts/documentation/api/
[Composer]: http://getcomposer.org/
[build-image]: http://img.shields.io/travis/eloquent/otis/develop.svg "Current build status for the develop branch"
[Current build status]: https://travis-ci.org/eloquent/otis
[coverage-image]: http://img.shields.io/coveralls/eloquent/otis/develop.svg "Current test coverage for the develop branch"
[Current coverage status]: https://coveralls.io/r/eloquent/otis
[eloquent/otis]: https://packagist.org/packages/eloquent/otis
[Semantic versioning]: http://semver.org/
[version-image]: http://img.shields.io/:semver-0.3.0-yellow.svg "This project uses semantic versioning"

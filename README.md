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
(counter-based passwords as covered in [RFC 4226]).

In addition, *Otis* provides tools for generating the URI format understood by
[Google Authenticator] and other compatible OTP apps, as well as URIs for QR
code generation services to further ease integration.

<!-- References -->

[API documentation]: http://lqnt.co/otis/artifacts/documentation/api/
[Composer]: http://getcomposer.org/
[eloquent/otis]: https://packagist.org/packages/eloquent/otis
[Google Authenticator]: http://en.wikipedia.org/wiki/Google_Authenticator
[HOTP]: http://en.wikipedia.org/wiki/HMAC-based_One-time_Password_Algorithm
[one-time password]: http://en.wikipedia.org/wiki/One-time_password
[RFC 4226]: http://tools.ietf.org/html/rfc4226
[RFC 6238]: http://tools.ietf.org/html/rfc6238
[TOTP]: http://en.wikipedia.org/wiki/Time-based_One-time_Password_Algorithm
[two-factor authentication]: http://en.wikipedia.org/wiki/Multi-factor_authentication

[Build Status]: https://api.travis-ci.org/eloquent/otis.png?branch=master
[Latest build]: https://travis-ci.org/eloquent/otis
[SemVer]: http://semver.org/
[Test coverage report]: https://coveralls.io/r/eloquent/otis
[Test Coverage]: https://coveralls.io/repos/eloquent/otis/badge.png?branch=master
[Uses Semantic Versioning]: http://b.repl.ca/v1/semver-yes-brightgreen.png

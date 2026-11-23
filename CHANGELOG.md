# Changelog

All notable changes to `laravel-rating` will be documented in this file.

## Version 1.4.1

- Apply StyleCI code style fixes

## Version 1.4.0

- Add support for Laravel 12 and 13
- Add support for PHP 8.1 up to 8.5
- Drop support for Laravel 5.5 up to 8 (end of life)
- Drop support for PHP below 8.2
- The published migration now creates the `rating` column as `decimal(9, 2)` (Laravel 11+ no longer accepts a scale for `float` columns)
- Replace Travis CI with GitHub Actions

## Version 1.0

- initial release

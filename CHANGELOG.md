# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [0.3.0] - 2026-03-25

### Fixed

- `Decoder`: removed duplicate `mergeFromString` call that doubled all repeated fields (keys, features, coords) and lacked error handling.
- `Decoder`: corrected `array_slice` length argument in `decodeMultiLine` — was passing an end index instead of a count, breaking all multi-ring geometries beyond the first ring.
- `Decoder`: `decodeMultiPolygon` was slicing `$coords` instead of `$lengths` to get per-ring point counts, corrupting any MultiPolygon with more than one polygon.
- `Encoder`: `encodeToFile` did not forward `$precision` and `$dim` to `encode()`, always encoding with the defaults.
- `Encoder`: `$keys` deduplication never matched (compared strings against `true` values with strict mode); keys were never deduplicated and `$keys` was never reset between calls.
- `Tests`: `is_dir()` was called with a bare filename instead of the full path.

### Changed

- CI: updated `actions/checkout` to v6, `actions/cache` to v5, added PHP 8.5 to test matrix.

## [0.2.0] - 2024-11-08

### Changed

- Set PHP 8.2 as minimum version.
- Updated dependencies; migrated test suite to Pest v3.
- Updated CI matrix to test under multiple PHP versions.

## [0.1.0] - Initial release

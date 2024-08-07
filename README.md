# php-geobuf

[![php-geobuf test suite](https://github.com/mbolli/php-geobuf/actions/workflows/php-test.yml/badge.svg)](https://github.com/mbolli/php-geobuf/actions/workflows/php-test.yml) [![Latest Stable Version](https://poser.pugx.org/mbolli/php-geobuf/v)](https://packagist.org/packages/mbolli/php-geobuf) [![Total Downloads](https://poser.pugx.org/mbolli/php-geobuf/downloads)](https://packagist.org/packages/mbolli/php-geobuf) [![Latest Unstable Version](https://poser.pugx.org/mbolli/php-geobuf/v/unstable)](https://packagist.org/packages/mbolli/php-geobuf) [![License](https://poser.pugx.org/mbolli/php-geobuf/license)](https://packagist.org/packages/mbolli/php-geobuf) [![PHP Version Require](https://poser.pugx.org/mbolli/php-geobuf/require/php)](https://packagist.org/packages/mbolli/php-geobuf)

PHP library for the geobuf compact geospatial format.

This is essentially a PHP port of the great [pygeobuf](https://github.com/pygeobuf/pygeobuf).

Geobuf stores GeoJSON 6-8 times smaller and TopoJSON 2-3 times smaller. Depending on the `$precision` attribute, lossless compression is possible. More information about Geobuf is available in the [JS implementation](https://github.com/mapbox/geobuf) or the [Python implementation](https://github.com/pygeobuf/pygeobuf).

**Quick size comparison:** An example 745 kB GeoJSON was converted to a 90 kB Geobuf file – more than 8 times less.

**Beware:** Experimental state – it works for my purposes but there probably are some bugs.

## Installation

```bash
composer require mbolli/php-geobuf
```

## Usage

The following methods are exposed:

### Encoder

- `encode()` reads a JSON string. Returns a geobuf-encoded string
  - string `$dataJson` a JSON string
- `encodeToFile()` reads a JSON string and writes to a file. Returns the filesize of the resulting file or false
  - string `$filePath` where to store the resulting geobuf file
  - string `$dataJson` a JSON string
- `encodeFileToBufFile()` reads from a JSON file and writes to a file. Returns the filesize of the resulting file or false
  - string `$jsonFile` path to the JSON file
  - string `$geobufFile` where to store the resulting geobuf file
- `encodeFileToBuf()` reads from a JSON file. Returns a geobuf-encoded string
  - string `$fileName` path to the JSON file

All encoding methods support the following two non-mandatory arguments:

- int `$precision` max number of digits after the decimal point in coordinates, 6 by default (10 cm).
- int `$dim` number of dimensions in coordinates, 2 by default.

### Decoder

- `decodeToArray()` returns a PHP array
  - string `$encodedInput` geobuf input
- `decodeFileToArray()` returns a PHP array
  - string `$fileName` path to the geobuf file
- `decodeToJson()` returns a JSON string
  - string `$encodedInput` geobuf input
- `decodeFileToJson()` returns a JSON string
  - string `$fileName` path to the geobuf file
- `decodeFileToJsonFile()` writes to a file and returns the filesize of the resulting JSON file or false
  - string `$geobufFile` path to the geobuf file
  - string `$jsonFile` where to store the resulting JSON file
  
### Example

```php
<?php

require_once('./vendor/autoload.php');

$jsonFile = './my.geojson';
$geobufFile = basename($jsonFile) . '.geobuf';
try {
    // encodes a json string to geobuf
    $geobufString = \MBolli\PhpGeobuf\Encoder::encode(
        file_get_contents($jsonFile), // (string) a json string 
        6, // (int) precision: max number of digits after the decimal point in coordinates, 6 by default
        2 // (int) dimensions: number of dimensions in coordinates, 2 by default.
    );
    
    // decodes a geobuf file to json
    $jsonString = \MBolli\PhpGeobuf\Decoder::decodeToJson(
        file_get_contents($geobufFile) // (string) expects a geobuf string
    )

} catch (\MBolli\PhpGeobuf\GeobufException $e) {
    var_dump($e);
} catch (\Throwable $e) {
    var_dump($e);
}
```

## Contribute

Pull requests are encouraged. Code style is enforced by PHP-CS-Fixer:

```bash
composer run lint # lint source files and show problems (read-only)
composer run lint-diff # lint source files and show diff to the files fixed state (read-only)
composer run fix # lint source files and fix the problems
composer run test # execute all tests
composer run analyse # run phpstan static analyzer
```

If the PR is about the Encoder or Decoder, please add a test JSON to the `tests/geojson` folder. The test suite will automatically pick it up and test it when executed.

## Background: Proto compilation

Classes were generated by the [proto compiler](https://developers.google.com/protocol-buffers) using this command:

```bash
bin/protoc --proto_path=src --php_out=build src/geobuf.proto
```

Used was [this proto file](./geobuf.proto), lightly modified from the [mapbox/geobuf proto file](https://github.com/mapbox/geobuf/blob/master/geobuf.proto) for proto3 compatibility and automated namespace generation.

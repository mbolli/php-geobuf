<?php

namespace MBolli\PhpGeobuf;

use Google\Protobuf\Internal\RepeatedField;
use MBolli\PhpGeobuf\Data\Feature;
use MBolli\PhpGeobuf\Data\FeatureCollection;
use MBolli\PhpGeobuf\Data\Geometry;
use MBolli\PhpGeobuf\Data\Value;

final class Decoder {
    private const GEOMETRY_TYPES = ['Point', 'MultiPoint', 'LineString', 'MultiLineString',
        'Polygon', 'MultiPolygon', 'GeometryCollection', ];

    /** @var Data */
    private static $data;
    private static $e;
    private static $dim;

    /**
     * Decode the geobuf file `$fileName` and save the resulting json as `$jsonFile`.
     * Returns the resulting file size if successful, or false.
     *
     * @return false|int
     *
     * @throws GeobufException
     */
    public static function decodeFileToJsonFile(string $geobufFile, string $jsonFile): bool|int {
        return file_put_contents($jsonFile, self::decodeFileToJson($geobufFile));
    }

    /**
     * Decode the geobuf file `$fileName` to a json string which is returned.
     *
     * @throws GeobufException
     */
    public static function decodeFileToJson(string $fileName): string {
        return self::decodeToJson(file_get_contents($fileName));
    }

    /**
     * Decode the geobuf input `$encodedInput` to a json string which is returned.
     *
     * @throws GeobufException
     */
    public static function decodeToJson(string $encodedInput): string {
        return json_encode(self::decodeToArray($encodedInput));
    }

    /**
     * Decode the geobuf file `$fileName` and return the array.
     *
     * @throws GeobufException
     */
    public static function decodeFileToArray(string $fileName): array {
        return self::decodeToArray(file_get_contents($fileName));
    }

    /**
     * Decode the geobuf input `$encodedInput` and return the array.
     *
     * @throws GeobufException
     */
    public static function decodeToArray(string $encodedInput): array {
        self::$data = new Data();
        self::$data->mergeFromString($encodedInput);
        self::$e = 10 ** self::$data->getPrecision();
        self::$dim = self::$data->getDimensions();

        try {
            self::$data->mergeFromString($encodedInput);
        } catch (\Exception $e) {
            throw new GeobufException('Error while decoding Geobuf: ' . $e->getMessage(), 0, $e);
        }

        switch (self::$data->getDataType()) {
            case 'feature_collection':
                return self::decodeFeatureCollection(self::$data->getFeatureCollection());
            case 'feature':
                return self::decodeFeature(self::$data->getFeature());
            case 'geometry':
                return self::decodeGeometry(self::$data->getGeometry());
        }

        throw new GeobufException('Unknown data type ' . self::$data->getDataType());
    }

    private static function decodeFeatureCollection(FeatureCollection $featureCollection): array {
        $obj = ['type' => 'FeatureCollection', 'features' => []];

        self::decodeProperties($featureCollection->getCustomProperties(), $featureCollection->getValues(), $obj);

        foreach ($featureCollection->getFeatures() as $feature) {
            $obj['features'][] = self::decodeFeature($feature);
        }

        return $obj;
    }

    private static function decodeFeature(Feature $feature): array {
        $obj = ['type' => 'Feature'];

        self::decodeProperties($feature->getCustomProperties(), $feature->getValues(), $obj);
        self::decodeId($feature, $obj);

        $obj['geometry'] = self::decodeGeometry($feature->getGeometry());

        if (\count($feature->getProperties()) > 0) {
            $obj['properties'] = self::decodeProperties($feature->getProperties(), $feature->getValues());
        }

        return $obj;
    }

    /**
     * @param RepeatedField|Value[] $values
     */
    private static function decodeProperties(array|RepeatedField $props, array|RepeatedField $values, array &$dest = []): array {
        $numProps = \count($props);
        if ($numProps === 0) {
            return $dest;
        }

        $keys = self::$data->getKeys();
        $r = $numProps > 2 ? range(0, $numProps - 1, 2) : [0];

        foreach ($r as $i) {
            $key = (string) $keys[$props[$i]];
            $val = $values[$props[$i + 1]];
            $valueType = $val->getValueType();

            if ($valueType === 'string_value') {
                $dest[$key] = $val->getStringValue();
            } elseif ($valueType === 'double_value') {
                $dest[$key] = $val->getDoubleValue();
            } elseif ($valueType === 'pos_int_value') {
                $dest[$key] = $val->getPosIntValue();
            } elseif ($valueType === 'neg_int_value') {
                $dest[$key] = -$val->getNegIntValue();
            } elseif ($valueType === 'bool_value') {
                $dest[$key] = $val->getBoolValue();
            } elseif ($valueType === 'json_value') {
                $dest[$key] = json_decode($val->getJsonValue(), true);
            }
        }

        return $dest;
    }

    private static function decodeId(Feature $feature, array &$objJson): void {
        $idType = $feature->getIdType();
        if ($idType === 'id') {
            $objJson['id'] = $feature->getId();
        } elseif ($idType === 'int_id') {
            $objJson['id'] = $feature->getIntId();
        }
    }

    private static function decodeGeometry(?Geometry $geometry): ?array {
        if ($geometry === null) {
            return null;
        }
        $gt = self::GEOMETRY_TYPES[$geometry->getType()];
        $obj = ['type' => $gt];

        self::decodeProperties($geometry->getCustomProperties(), $geometry->getValues(), $obj);

        switch ($gt) {
            case 'GeometryCollection':
                $obj['geometries'] = array_map(
                    fn ($g) => self::decodeGeometry($g),
                    $geometry->getGeometries()
                );
                break;
            case 'Point':
                $obj['coordinates'] = self::decodePoint($geometry->getCoords());
                break;
            case 'LineString':
            case 'MultiPoint':
                $obj['coordinates'] = self::decodeLine($geometry->getCoords());
                break;
            case 'MultiLineString':
            case 'Polygon':
                $obj['coordinates'] = self::decodeMultiLine($geometry, $gt === 'Polygon');
                break;
            case 'MultiPolygon':
                $obj['coordinates'] = self::decodeMultiPolygon($geometry);
        }

        return $obj;
    }

    private static function decodeCoord(float $coord): float {
        return $coord / self::$e;
    }

    /**
     * @param array|RepeatedField $coords
     */
    private static function decodePoint($coords): array {
        $return = [];
        foreach ($coords as $coord) { // can't use array_map as $coords might be a RepeatedField
            $return[] = self::decodeCoord((float) $coord);
        }

        return $return;
    }

    /**
     * @param array|RepeatedField $coords
     */
    private static function decodeLine($coords, ?bool $isClosed = false): array {
        $obj = [];
        $numCoords = \count($coords);
        $r = range(0, self::$dim - 1);
        $r2 = $numCoords > self::$dim ? range(0, $numCoords - 1, self::$dim) : [0];
        $p0 = array_fill(0, self::$dim, 0);

        foreach ($r2 as $i) {
            $p = array_map(
                fn ($j) => ($p0[$j] ?? 0) + ($coords[$i + $j] ?? 0),
                $r
            );
            $obj[] = self::decodePoint($p);
            $p0 = $p;
        }

        if ($isClosed === true) {
            $p = array_map(fn ($j) => $coords[$j], $r);
            $obj[] = self::decodePoint($p);
        }

        return $obj;
    }

    private static function decodeMultiLine(Geometry $geometry, ?bool $isClosed = false): array {
        $coords = $geometry->getCoords();
        if (\count($geometry->getLengths()) === 0) {
            return [self::decodeLine($coords, $isClosed)];
        }

        $obj = [];
        $i = 0;

        foreach ($geometry->getLengths() as $length) {
            $obj[] = self::decodeLine(\array_slice($coords, $i, $i + $length * self::$dim), $isClosed);
            $i += $length * self::$dim;
        }

        return $obj;
    }

    private static function decodeMultiPolygon(Geometry $geometry): array {
        $lengths = $geometry->getLengths();
        $coords = $geometry->getCoords();
        if (\count($lengths) === 0) {
            return [[self::decodeLine($coords, true)]];
        }

        $obj = [];
        $i = 0;
        $j = 1;
        $numPolygons = $lengths[0];

        foreach (range(0, $numPolygons - 1) as $n) {
            $numRings = $lengths[$j];
            ++$j;
            $rings = [];

            foreach (\array_slice($coords, $j, $j + $numRings) as $l) {
                $rings[] = self::decodeLine(\array_slice($coords, $i, $i + $l * self::$dim), true);
                ++$j;
                $i += $l * self::$dim;
            }

            $obj[] = $rings;
        }

        return $obj;
    }
}

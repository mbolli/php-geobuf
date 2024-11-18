<?php

namespace MBolli\PhpGeobuf;

use MBolli\PhpGeobuf\Data\Feature;
use MBolli\PhpGeobuf\Data\FeatureCollection;
use MBolli\PhpGeobuf\Data\Geometry;
use MBolli\PhpGeobuf\Data\Value;
use MBolli\PhpGeobuf\Interfaces\IHasCustomProperties;
use MBolli\PhpGeobuf\Interfaces\IHasProperties;

final class Encoder {
    private const GEOMETRY_TYPES = [
        'Point' => 0,
        'MultiPoint' => 1,
        'LineString' => 2,
        'MultiLineString' => 3,
        'Polygon' => 4,
        'MultiPolygon' => 5,
        'GeometryCollection' => 6,
    ];

    private static $json;
    /** @var Data */
    private static $data;
    private static $dim;
    private static $e;
    private static $keys = [];

    /**
     * encodes the JSON file `$jsonFile` to Geobuf and stores it in the file `$geobufFile`.
     * returns the stored file size on success, or false on failure.
     *
     * @return false|int
     *
     * @throws GeobufException
     */
    public static function encodeFileToBufFile(string $jsonFile, string $geobufFile, int $precision = 6, int $dim = 2): bool|int {
        return file_put_contents($geobufFile, self::encodeFileToBuf($jsonFile, $precision, $dim));
    }

    /**
     * encodes the JSON file `$fileName` and returns the geobuf-encoded string.
     *
     * @throws GeobufException
     */
    public static function encodeFileToBuf(string $fileName, int $precision = 6, int $dim = 2): string {
        return self::encode(file_get_contents($fileName), $precision, $dim);
    }

    /**
     * encodes a json string `$dataJson` to Geobuf and stores it in the file `$filePath`.
     * returns the stored file size on success, or false on failure.
     *
     * @return false|int
     *
     * @throws GeobufException
     */
    public static function encodeToFile(string $filePath, string $dataJson, int $precision = 6, int $dim = 2): bool|int {
        return file_put_contents($filePath, self::encode($dataJson));
    }

    /**
     * encodes a json string `$dataJson` to Geobuf and returns the resulting string.
     *
     * @throws GeobufException
     */
    public static function encode(string $dataJson, int $precision = 6, int $dim = 2): string {
        try {
            $geoJson = json_decode($dataJson, true, 512, \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new GeobufException('Error while decoding GeoJSON: ' . $e->getMessage(), 0, $e);
        }

        self::$data = new Data();
        self::$data->setDimensions($dim);
        self::$data->setPrecision($precision);

        self::$json = $geoJson;
        self::$dim = $dim;
        self::$e = 10 ** $precision; // multiplier for converting coordinates into integers

        $dataType = self::$json['type'];
        if ($dataType === 'FeatureCollection') {
            self::$data->setFeatureCollection(self::encodeFeatureCollection());
        } elseif ($dataType === 'Feature') {
            self::$data->setFeature(self::encodeFeature(self::$json));
        } else {
            self::$data->setGeometry(self::encodeGeometry(self::$json));
        }

        return self::$data->serializeToString();
    }

    private static function encodeFeatureCollection(): FeatureCollection {
        $featureCollection = new FeatureCollection();
        self::encodeCustomProperties($featureCollection, self::$json, ['type', 'features']);
        $features = [];
        foreach (self::$json['features'] as $featureJson) {
            $features[] = self::encodeFeature($featureJson);
        }
        $featureCollection->setFeatures($features);

        return $featureCollection;
    }

    private static function encodeFeature(array $featureJson): Feature {
        $feature = new Feature();

        // encode id
        self::encodeId($feature, $featureJson['id'] ?? null);

        // encode properties
        if (\array_key_exists('properties', $featureJson)) {
            self::encodeProperties($feature, $featureJson['properties']);
        }

        // encode custom properties
        self::encodeCustomProperties($feature, $featureJson, ['type', 'id', 'properties', 'geometry']);

        // encode geometry
        if (\array_key_exists('geometry', $featureJson)) {
            if ($featureJson['geometry'] === null) {
                $feature->setGeometry(null);
            } else {
                $feature->setGeometry(self::encodeGeometry($featureJson['geometry']));
            }
        }

        return $feature;
    }

    private static function encodeGeometry(array $geometryJson): Geometry {
        $geometry = new Geometry();
        $gt = $geometryJson['type'];
        $coords = $geometryJson['coordinates'];

        $geometry->setType(self::GEOMETRY_TYPES[$gt]);

        self::encodeCustomProperties(
            $geometry,
            $geometryJson,
            ['type', 'id', 'coordinates', 'arcs', 'geometries', 'properties']
        );

        switch ($gt) {
            case 'GeometryCollection':
                $geometries = [];
                foreach ($geometryJson['geometries'] as $geom) {
                    $geometries[] = self::encodeGeometry($geom);
                }
                $geometry->setGeometries($geometries);
                break;
            case 'Point':
                $geometry->setCoords(self::addPoint($coords));
                break;
            case 'LineString':
            case 'MultiPoint':
                $geometry->setCoords(self::addLine($coords));
                break;
            case 'MultiLineString':
                self::addMultiLine($geometry, $coords);
                break;
            case 'Polygon':
                self::addMultiLine($geometry, $coords, true);
                break;
            case 'MultiPolygon':
                self::addMultiPolygon($geometry, $coords);
                break;
        }

        return $geometry;
    }

    private static function encodeProperties(IHasProperties $obj, ?array $propsJson): void {
        if ($propsJson === null) {
            return;
        }

        if (empty($propsJson)) {
            $obj->setProperties([]);

            return;
        }

        foreach ($propsJson as $key => $val) {
            self::encodeProperty($key, $val, $obj);
        }
    }

    private static function encodeCustomProperties(IHasCustomProperties $obj, array $objJson, array $exclude): void {
        foreach ($objJson as $key => $val) {
            if (!\in_array($key, $exclude, true)) {
                self::encodeProperty($key, $val, $obj, true);
            }
        }
    }

    /**
     * @param IHasCustomProperties|IHasProperties $obj
     */
    private static function encodeProperty(string $key, $val, $obj, bool $custom = false): void {
        $keyIndex = array_search($key, self::$keys, true);

        if ($keyIndex === false) {
            self::$keys[$key] = true;
            self::$data->addKey($key);
            $keyIndex = \count(self::$data->getKeys()) - 1;
        }

        $value = new Value();

        if (\is_string($val)) {
            $value->setStringValue($val);
        } elseif (\is_float($val)) {
            $value->setDoubleValue($val);
        } elseif (\is_int($val)) {
            self::encodeInt($value, $val);
        } elseif (\is_bool($val)) {
            $value->setBoolValue($val);
        } else {
            $value->setJsonValue(json_encode($val));
        }

        $obj->addValue($value);

        $valuesIndex = \count($obj->getValues()) - 1;
        if ($custom === true) {
            $obj->addCustomProperty($keyIndex);
            $obj->addCustomProperty($valuesIndex);
        } else {
            $obj->addProperty($keyIndex);
            $obj->addProperty($valuesIndex);
        }
    }

    private static function encodeInt(Value $value, int $val): void {
        if ($val >= 0) {
            $value->setPosIntValue($val);
        } else {
            $value->setNegIntValue(-$val);
        }
    }

    private static function encodeId(Feature $feature, $id): void {
        if ($id === null) {
            return;
        }
        if (\is_int($id)) {
            $feature->setIntId($id);
        } else {
            $feature->setId($id);
        }
    }

    private static function addCoord(array &$coords, $coord): void {
        $coords[] = (int) round($coord * self::$e);
    }

    private static function addPoint($point): array {
        $coords = [];
        foreach ($point as $x) {
            self::addCoord($coords, $x);
        }

        return $coords;
    }

    private static function addLine(array $points, bool $isClosed = false): array {
        $coords = [];
        $sum = array_fill(0, self::$dim, 0);
        for ($i = 0; $i < \count($points) - (int) $isClosed; ++$i) {
            for ($j = 0; $j < self::$dim; ++$j) {
                $n = (int) (round($points[$i][$j] * self::$e) - $sum[$j]);
                $coords[] = $n;
                $sum[$j] += $n;
            }
        }

        return $coords;
    }

    private static function addMultiLine(Geometry $geometry, array $lines, bool $isClosed = false): void {
        if (\count($lines) !== 1) {
            foreach ($lines as $points) {
                $geometry->addLength(\count($points) - (int) $isClosed);
            }
        }

        $coords = [];
        foreach ($lines as $points) {
            $coords = array_merge($coords, self::addLine($points, $isClosed));
        }
        $geometry->setCoords($coords);
    }

    private static function addMultiPolygon(Geometry $geometry, array $polygons): void {
        if (\count($polygons) !== 1 || \count($polygons[0]) !== 1) {
            $geometry->addLength(\count($polygons));
            foreach ($polygons as $rings) {
                $geometry->addLength(\count($rings));

                foreach ($rings as $points) {
                    $geometry->addLength(\count($points) - 1);
                }
            }
        }

        $coords = [];
        foreach ($polygons as $rings) {
            foreach ($rings as $points) {
                $coords = array_merge($coords, self::addLine($points, true));
            }
        }
        $geometry->setCoords($coords);
    }
}

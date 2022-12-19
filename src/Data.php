<?php

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: geobuf.proto

namespace MBolli\PhpGeobuf;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\Message;
use Google\Protobuf\Internal\RepeatedField;
use GPBMetadata\Geobuf;
use MBolli\PhpGeobuf\Data\Feature;
use MBolli\PhpGeobuf\Data\FeatureCollection;
use MBolli\PhpGeobuf\Data\Geometry;

/**
 * Generated from protobuf message <code>MBolli.PhpGeobuf.Data</code>.
 */
class Data extends Message {
    /**
     * global arrays of unique keys.
     *
     * Generated from protobuf field <code>repeated string keys = 1;</code>
     */
    private $keys;
    /**
     *[default = 2]; // max coordinate dimensions.
     *
     * Generated from protobuf field <code>optional uint32 dimensions = 2;</code>
     */
    protected $dimensions = 2;
    /**
     *[default = 6]; // number of digits after decimal point for coordinates.
     *
     * Generated from protobuf field <code>optional uint32 precision = 3;</code>
     */
    protected $precision = 6;
    protected $data_type;

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     *     @var RepeatedField|string[] $keys
     *           global arrays of unique keys
     *     @var int $dimensions
     *          [default = 2]; // max coordinate dimensions
     *     @var int $precision
     *          [default = 6]; // number of digits after decimal point for coordinates
     *     @var FeatureCollection $feature_collection
     *     @var Feature $feature
     *     @var Geometry $geometry
     * }
     */
    public function __construct($data = null) {
        Geobuf::initOnce();
        parent::__construct($data);
    }

    /**
     * global arrays of unique keys.
     *
     * Generated from protobuf field <code>repeated string keys = 1;</code>
     *
     * @return RepeatedField
     */
    public function getKeys() {
        return $this->keys;
    }

    /**
     * global arrays of unique keys.
     *
     * Generated from protobuf field <code>repeated string keys = 1;</code>
     *
     * @param RepeatedField|string[] $var
     *
     * @return $this
     */
    public function setKeys($var) {
        $arr = GPBUtil::checkRepeatedField($var, GPBType::STRING);
        $this->keys = $arr;

        return $this;
    }

    /**
     * add to the global arrays of unique keys.
     *
     * @param mixed $var
     *
     * @return $this
     */
    public function addKey($var) {
        $this->keys[] = $var;

        return $this;
    }

    /**
     *[default = 2]; // max coordinate dimensions.
     *
     * Generated from protobuf field <code>optional uint32 dimensions = 2;</code>
     *
     * @return int
     */
    public function getDimensions() {
        return $this->dimensions ?? 0;
    }

    public function hasDimensions() {
        return isset($this->dimensions);
    }

    public function clearDimensions(): void {
        $this->dimensions = null;
    }

    /**
     *[default = 2]; // max coordinate dimensions.
     *
     * Generated from protobuf field <code>optional uint32 dimensions = 2;</code>
     *
     * @param int $var
     *
     * @return $this
     */
    public function setDimensions($var) {
        GPBUtil::checkUint32($var);
        $this->dimensions = $var;

        return $this;
    }

    /**
     *[default = 6]; // number of digits after decimal point for coordinates.
     *
     * Generated from protobuf field <code>optional uint32 precision = 3;</code>
     *
     * @return int
     */
    public function getPrecision() {
        return $this->precision ?? 0;
    }

    public function hasPrecision() {
        return isset($this->precision);
    }

    public function clearPrecision(): void {
        $this->precision = null;
    }

    /**
     *[default = 6]; // number of digits after decimal point for coordinates.
     *
     * Generated from protobuf field <code>optional uint32 precision = 3;</code>
     *
     * @param int $var
     *
     * @return $this
     */
    public function setPrecision($var) {
        GPBUtil::checkUint32($var);
        $this->precision = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.FeatureCollection feature_collection = 4;</code>.
     *
     * @return FeatureCollection|null
     */
    public function getFeatureCollection() {
        return $this->readOneof(4);
    }

    public function hasFeatureCollection() {
        return $this->hasOneof(4);
    }

    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.FeatureCollection feature_collection = 4;</code>.
     *
     * @param FeatureCollection $var
     *
     * @return $this
     */
    public function setFeatureCollection($var) {
        GPBUtil::checkMessage($var, FeatureCollection::class);
        $this->writeOneof(4, $var);

        return $this;
    }

    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.Feature feature = 5;</code>.
     *
     * @return Feature|null
     */
    public function getFeature() {
        return $this->readOneof(5);
    }

    public function hasFeature() {
        return $this->hasOneof(5);
    }

    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.Feature feature = 5;</code>.
     *
     * @param Feature $var
     *
     * @return $this
     */
    public function setFeature($var) {
        GPBUtil::checkMessage($var, Feature::class);
        $this->writeOneof(5, $var);

        return $this;
    }

    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.Geometry geometry = 6;</code>.
     *
     * @return Geometry|null
     */
    public function getGeometry() {
        return $this->readOneof(6);
    }

    public function hasGeometry() {
        return $this->hasOneof(6);
    }

    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.Geometry geometry = 6;</code>.
     *
     * @param Geometry $var
     *
     * @return $this
     */
    public function setGeometry($var) {
        GPBUtil::checkMessage($var, Geometry::class);
        $this->writeOneof(6, $var);

        return $this;
    }

    /**
     * @return string
     */
    public function getDataType() {
        return $this->whichOneof('data_type');
    }
}

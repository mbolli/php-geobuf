<?php

// Generated by the protocol buffer compiler.  DO NOT EDIT!
// source: geobuf.proto

namespace MBolli\PhpGeobuf\Data;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\GPBUtil;
use Google\Protobuf\Internal\Message;
use Google\Protobuf\Internal\RepeatedField;
use GPBMetadata\Geobuf;
use MBolli\PhpGeobuf\Interfaces\IHasCustomProperties;
use MBolli\PhpGeobuf\Interfaces\IHasProperties;

/**
 * Generated from protobuf message <code>MBolli.PhpGeobuf.Data.Feature</code>.
 */
class Feature extends Message implements IHasProperties, IHasCustomProperties {
    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.Geometry geometry = 1;</code>.
     */
    protected $geometry = null;
    /**
     * unique values.
     *
     * Generated from protobuf field <code>repeated .MBolli.PhpGeobuf.Data.Value values = 13;</code>
     */
    private $values;
    /**
     * pairs of key/value indexes.
     *
     * Generated from protobuf field <code>repeated uint32 properties = 14 [packed = true];</code>
     */
    private $properties;
    /**
     * arbitrary properties.
     *
     * Generated from protobuf field <code>repeated uint32 custom_properties = 15 [packed = true];</code>
     */
    private $custom_properties;
    protected $id_type;

    /**
     * Constructor.
     *
     * @param array $data {
     *                    Optional. Data for populating the Message object.
     *
     *     @var Geometry $geometry
     *     @var string $id
     *     @var int|string $int_id
     *     @var RepeatedField|Value[] $values
     *           unique values
     *     @var int[]|RepeatedField $properties
     *           pairs of key/value indexes
     *     @var int[]|RepeatedField $custom_properties
     *           arbitrary properties
     * }
     */
    public function __construct($data = null) {
        Geobuf::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.Geometry geometry = 1;</code>.
     *
     * @return null|Geometry
     */
    public function getGeometry() {
        return $this->geometry;
    }

    public function hasGeometry() {
        return isset($this->geometry);
    }

    public function clearGeometry(): void {
        unset($this->geometry);
    }

    /**
     * Generated from protobuf field <code>.MBolli.PhpGeobuf.Data.Geometry geometry = 1;</code>.
     *
     * @param Geometry $var
     *
     * @return $this
     */
    public function setGeometry($var) {
        GPBUtil::checkMessage($var, Geometry::class);
        $this->geometry = $var;

        return $this;
    }

    /**
     * Generated from protobuf field <code>string id = 11;</code>.
     *
     * @return string
     */
    public function getId() {
        return $this->readOneof(11);
    }

    public function hasId() {
        return $this->hasOneof(11);
    }

    /**
     * Generated from protobuf field <code>string id = 11;</code>.
     *
     * @param string $var
     *
     * @return $this
     */
    public function setId($var) {
        GPBUtil::checkString($var, true);
        $this->writeOneof(11, $var);

        return $this;
    }

    /**
     * Generated from protobuf field <code>sint64 int_id = 12;</code>.
     *
     * @return int|string
     */
    public function getIntId() {
        return $this->readOneof(12);
    }

    public function hasIntId() {
        return $this->hasOneof(12);
    }

    /**
     * Generated from protobuf field <code>sint64 int_id = 12;</code>.
     *
     * @param int|string $var
     *
     * @return $this
     */
    public function setIntId($var) {
        GPBUtil::checkInt64($var);
        $this->writeOneof(12, $var);

        return $this;
    }

    /**
     * unique values.
     *
     * Generated from protobuf field <code>repeated .MBolli.PhpGeobuf.Data.Value values = 13;</code>
     *
     * @return RepeatedField
     */
    public function getValues() {
        return $this->values;
    }

    /**
     * unique values.
     *
     * Generated from protobuf field <code>repeated .MBolli.PhpGeobuf.Data.Value values = 13;</code>
     *
     * @param RepeatedField|Value[] $var
     *
     * @return $this
     */
    public function setValues($var) {
        $arr = GPBUtil::checkRepeatedField($var, GPBType::MESSAGE, Value::class);
        $this->values = $arr;

        return $this;
    }

    /**
     * add unique value.
     *
     * @return $this
     */
    public function addValue($var) {
        $this->values[] = $var;

        return $this;
    }

    /**
     * pairs of key/value indexes.
     *
     * Generated from protobuf field <code>repeated uint32 properties = 14 [packed = true];</code>
     *
     * @return RepeatedField
     */
    public function getProperties() {
        return $this->properties;
    }

    /**
     * pairs of key/value indexes.
     *
     * Generated from protobuf field <code>repeated uint32 properties = 14 [packed = true];</code>
     *
     * @param int[]|RepeatedField $var
     *
     * @return $this
     */
    public function setProperties($var) {
        $arr = GPBUtil::checkRepeatedField($var, GPBType::UINT32);
        $this->properties = $arr;

        return $this;
    }

    /**
     * Add property.
     *
     * @return $this
     */
    public function addProperty($var) {
        $this->properties[] = $var;

        return $this;
    }

    /**
     * arbitrary properties.
     *
     * Generated from protobuf field <code>repeated uint32 custom_properties = 15 [packed = true];</code>
     *
     * @return RepeatedField
     */
    public function getCustomProperties() {
        return $this->custom_properties;
    }

    /**
     * arbitrary properties.
     *
     * Generated from protobuf field <code>repeated uint32 custom_properties = 15 [packed = true];</code>
     *
     * @param int[]|RepeatedField $var
     *
     * @return $this
     */
    public function setCustomProperties($var) {
        $arr = GPBUtil::checkRepeatedField($var, GPBType::UINT32);
        $this->custom_properties = $arr;

        return $this;
    }

    /**
     * add arbitrary property.
     *
     * @return $this
     */
    public function addCustomProperty($var) {
        $this->custom_properties[] = $var;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdType() {
        return $this->whichOneof('id_type');
    }
}

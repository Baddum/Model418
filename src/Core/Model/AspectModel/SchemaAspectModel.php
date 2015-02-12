<?php

namespace Baddum\Model418\Core\Model\AspectModel;

abstract class SchemaAspectModel extends AspectModel
{


    /* ATTRIBUTES
     *************************************************************************/
    protected static $_schema = array();


    /* GETTER & SETTER
     *************************************************************************/
    public function offsetSet($name, $value)
    {
        if (!$this->hasSchema($name)) {
            throw new \RuntimeException('Invalid attribute name: ' . $name);
        }
        parent::offsetSet($name, $value);
        return $this;
    }


    /* INITIALIZATION
     *************************************************************************/
    public function __construct()
    {
        parent::__construct();
        if (!$this->hasSchema()) {
            $schema = $this->initSchema();
            $this->setSchema($schema);
        }
        foreach ($this->getSchema() as $name => $value) {
            $this->set($name, $value);
        }
    }
    
    public function initByData($data)
    {
        if (!$this->hasSchema()) {
            return parent::initByData($data);
        }
        foreach ($this->getSchema() as $name => $value) {
            if (isset($data[$name])) {
                $value = $data[$name];
            }
            $this->set($name, $value);
        }
        $this->initialize();
    }


    /* PROTECTED SCHEMA METHODS
     *************************************************************************/
    protected function initSchema()
    {
        throw new \LogicException('This method must be overridden');
    }

    protected function hasSchema($key = null)
    {
        if (!isset(static::$_schema[get_called_class()])) {
            return false;
        }
        if (is_null($key)) {
            return true;
        }
        $schema = static::$_schema[get_called_class()];
        if ($schema === false) {
            return true;
        }
        return isset($schema[$key]);
    }

    protected function uniformSchema($schema)
    {
        if ($schema === false) {
            return false;
        }
        $uniformSchema = array();
        foreach ($schema as $key => $defaultValue) {
            if (is_int($key) && is_string($defaultValue)) {
                $uniformSchema[$defaultValue] = '';
            } else {
                $uniformSchema[$key] = $defaultValue;
            }
        }
        return $uniformSchema;
    }

    protected function getSchema()
    {
        if (!$this->hasSchema()) {
            return array();
        }
        return static::$_schema[get_called_class()];
    }

    protected function setSchema($schema)
    {
        $schema = $this->uniformSchema($schema);
        static::$_schema[get_called_class()] = $schema;
        return $this;
    }
}
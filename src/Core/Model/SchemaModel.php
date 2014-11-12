<?php

namespace Model418\Core\Model;

class SchemaModel extends Model
{


    /* ATTRIBUTES
     *************************************************************************/
    protected static $_schema = array();


    /* GETTER & SETTER
     *************************************************************************/
    public function offsetSet($name, $value)
    {
        if ($this->hasSchema($name)) {
            parent::offsetSet($name, $value);
        }
        return $this;
    }


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($query = null)
    {
        parent::__construct($query);
        if (!$this->hasSchema()) {
            $schema = $this->initSchema();
            $this->setSchema($schema);
        }
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
        return isset($schema[$key]);
    }

    protected function uniformSchema($schema)
    {
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

    protected function setSchema($schema)
    {
        $schema = $this->uniformSchema($schema);
        static::$_schema[get_called_class()] = $schema;
        return $this;
    }

    protected function getSchema($key = null)
    {
        if (!$this->hasSchema()) {
            return array();
        }
        $schema = static::$_schema[get_called_class()];
        if (is_null($key)) {
            return $schema;
        }
        if (!$this->hasSchema($key)) {
            return array();
        }
        return $schema[$key];
    }
}
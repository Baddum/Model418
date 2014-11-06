<?php

namespace Elephant418\Model418;

use Elephant418\Model418\Core\ArrayObject;
use Elephant418\Model418\Core\IModel;

class Model extends ArrayObject implements IModel
{


    /* ATTRIBUTES
     *************************************************************************/
    public $id;
    protected static $_schema = array();
    protected static $_entity = array();


    /* GETTER & SETTER
     *************************************************************************/
    public function exists()
    {
        return !is_null($this->id);
    }

    public function name()
    {
        return $this->id;
    }

    public function offsetSet($name, $value)
    {
        if ($this->hasSchema($name)) {
            parent::offsetSet($name, $value);
        }
        return $this;
    }


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($entity = null)
    {
        parent::__construct();
        $this->injectEntity($entity);
        if (!$this->hasSchema()) {
            $schema = $this->initSchema();
            $this->setSchema($schema);
        }
    }

    public function initByData($data)
    {
        if (!isset($data['id'])) {
            throw new \Exception('Try to retrieve incomplete data');
        }
        $this->id = $data['id'];
        foreach ($this->getSchema() as $attributeName => $attributeValue) {
            if (isset($data[$attributeName])) {
                $attributeValue = $data[$attributeName];
            }
            $this->set($attributeName, $attributeValue);
        }
        $this->initialize();
        return $this;
    }

    protected function initialize()
    {
    }


    /* PUBLIC STORING METHODS
     *************************************************************************/
    public function save()
    {
        $id = $this->getEntity()->saveById($this->id, $this->toArray());
        if (is_null($this->id)) {
            $this->id = $id;
        }
        return $this;
    }

    public function delete()
    {
        if (!is_null($this->id)) {
            $this->getEntity()->deleteById($this->id);
        }
        return $this;
    }

    public function query()
    {
        return $this->getEntity();
    }


    /* PROTECTED SCHEMA METHODS
     *************************************************************************/
    protected function initSchema()
    {
        throw new \LogicException('This method must be overridden');
    }

    protected function hasSchema($key = null)
    {
        if (!isset(static::$_schema[get_class($this)])) {
            return false;
        }
        if (is_null($key)) {
            return true;
        }
        $schema = static::$_schema[get_class($this)];
        return isset($schema[$key]);
    }

    protected function setSchema($schema)
    {
        static::$_schema[get_class($this)] = $schema;
        return $this;
    }

    protected function getSchema($key = null)
    {
        if (!$this->hasSchema()) {
            return array();
        }
        $schema = static::$_schema[get_class($this)];
        if (is_null($key)) {
            return $schema;
        }
        if (!$this->hasSchema($key)) {
            return array();
        }
        return $schema[$key];
    }


    /* ENTITY METHODS
     *************************************************************************/
    protected function initEntity()
    {
        throw new \LogicException('This method must be overridden');
    }
    
    protected function injectEntity($entity) {
        if (!$this->hasEntity() && !$entity) {
            $entity = $this->initEntity();
        }
        if ($entity) {
            $this->setEntity($entity);
        }
    }

    protected function hasEntity()
    {
        return isset(static::$_entity[get_class($this)]);
    }

    protected function setEntity($entity)
    {
        static::$_entity[get_class($this)] = $entity;
        return $this;
    }

    protected function getEntity()
    {
        if (!$this->hasEntity()) {
            return null;
        }
        return static::$_entity[get_class($this)];
    }
}
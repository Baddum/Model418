<?php

namespace Elephant418\Packy;

class ArrayObject extends \ArrayObject
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $_schema = array();


    /* CONSTRUCTOR
     *************************************************************************/
    public function __construct()
    {
        $this->setFlags(\ArrayObject::ARRAY_AS_PROPS);
    }


    /* GETTER & SETTER
     *************************************************************************/
    public function get($name)
    {
        return $this->offsetGet($name);
    }

    public function set($name, $value)
    {
        return $this->offsetSet($name, $value);
    }

    public function offsetSet($name, $value)
    {
        if (isset($this->_schema[$name])) {
            parent::offsetSet($name, $value);
        }
        return $this;
    }

    public function offsetUnset($index)
    {
        $this->offsetSet($index, NULL);
    }

    public function toArray()
    {
        return $this->getArrayCopy();
    }
}
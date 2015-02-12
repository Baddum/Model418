<?php

namespace Baddum\Model418\Core;

class ArrayObject extends \ArrayObject
{


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($input = [])
    {
        parent::__construct($input, \ArrayObject::ARRAY_AS_PROPS);
    }


    /* GETTER & SETTER
     *************************************************************************/
    public function get($name)
    {
        return $this->offsetGet($name);
    }

    public function offsetGet($name)
    {
        if (!$this->offsetExists($name)) {
            return null;
        }
        return parent::offsetGet($name);
    }

    public function set($name, $value)
    {
        $this->offsetSet($name, $value);
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
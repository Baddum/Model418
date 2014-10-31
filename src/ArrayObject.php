<?php

namespace Elephant418\Model418;

class ArrayObject extends \ArrayObject
{


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

    public function offsetUnset($index)
    {
        $this->offsetSet($index, NULL);
    }

    public function toArray()
    {
        return $this->getArrayCopy();
    }
}
<?php

namespace Elephant418\Model418\Core\Model\AspectModel;

use Elephant418\Model418\Core\ArrayObject;

abstract class AspectModel extends ArrayObject
{


    /* GETTER & SETTER
     *************************************************************************/
    public function name()
    {
        return get_class();
    }


    /* INITIALIZATION
     *************************************************************************/
    public function initByData($data)
    {
        foreach ($data as $name => $value) {
            $this->set($name, $value);
        }
        $this->initialize();
        return $this;
    }

    protected function initialize()
    {
    }
}
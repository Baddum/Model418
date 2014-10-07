<?php

namespace Elephant418\Packy;

class Model extends ArrayObject
{


    /* ATTRIBUTES
     *************************************************************************/
    public $id;
    protected $_schema = array();
    protected $_entity = 'Elephant418\\Packy\\Entity';

    

    /* GETTER
     *************************************************************************/
    public function exists()
    {
        return ($this->id !== NULL);
    }

    public function name()
    {
        return $this->id;
    }

    

    /* INITIALIZATION
     *************************************************************************/
    public function initByData($data)
    {
        $this->id = $data['id'];
        foreach ($this->_schema as $attributeName => $attributeValue) {
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
}
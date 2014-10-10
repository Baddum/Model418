<?php

namespace Elephant418\Packy;

class Model extends ArrayObject
{
    use Entity;


    /* ATTRIBUTES
     *************************************************************************/
    public $id;
    protected $_schema = array();


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
    public function __construct($dataConnector = null)
    {
        parent::__construct();
        if (!$dataConnector) {
            $dataConnector = $this->initDataConnector();
        }
        $this->setDataConnector($dataConnector);
    }

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
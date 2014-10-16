<?php

namespace Test\Elephant418\Model418\Resources\SimpleCase;

use Elephant418\Model418\DataConnector\FileDataConnector as DataConnector;
use Elephant418\Model418\Model;

class ResourceModel extends Model
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $_schema = array('myName' => 'defaultValue');


    /* CONSTRUCTOR
     *************************************************************************/
    protected function initDataConnector()
    {
        $dataConnector = (new DataConnector)
            ->setDataFolder(__DIR__ . '/../data')
            ->setIdField('myName');
        return $dataConnector;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
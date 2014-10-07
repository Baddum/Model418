<?php

namespace Test\Elephant418\Packy\Resources\SimpleCase;

use Elephant418\Packy\DataConnector;
use Elephant418\Packy\Model;

class ResourceModel extends Model
{
    
    
    /* ATTRIBUTES
     *************************************************************************/
    protected $_schema = array('myName' => 'defaultValue');



    /* CONSTRUCTOR
     *************************************************************************/
    protected function initDataConnector()
    {
        $dataConnector = new DataConnector();
        $dataConnector->setDataFolder(__DIR__.'/../data');
        $this->setDataConnector($dataConnector);
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
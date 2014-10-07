<?php

namespace Test\Elephant418\Packy\Resources\SimpleModel;

use Elephant418\Packy\DataConnector;
use Elephant418\Packy\Model;

class TestModel extends Model
{
    
    
    /* ATTRIBUTES
     *************************************************************************/
    protected $_schema = array('myName' => 'defaultValue');



    /* CONSTRUCTOR
     *************************************************************************/
    public function __construct()
    {
        parent::__construct();
        $dataConnector = new DataConnector();
        $dataConnector->setDataFolder(__DIR__.'/data');
        $this->setDataConnector($dataConnector);
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
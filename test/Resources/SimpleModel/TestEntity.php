<?php

namespace Test\Elephant418\Packy\Resources\SimpleModel;

use Elephant418\Packy\DataConnector;
use Elephant418\Packy\Entity;

class TestEntity extends Entity 
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $_model = 'Test\\Elephant418\\Packy\\Resources\\SimpleModel\\TestModel';
    

    /* GETTER
     *************************************************************************/
    public function __construct()
    {
        $dataConnector = new DataConnector();
        $dataConnector->setDataFolder(__DIR__.'/data');
        $this->setDataConnector($dataConnector);
    }
}
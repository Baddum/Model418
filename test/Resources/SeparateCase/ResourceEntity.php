<?php

namespace Test\Elephant418\Packy\Resources\SeparateCase;

use Elephant418\Packy\DataConnector\FileDataConnector as DataConnector;

trait ResourceEntity
{


    /* CONSTRUCTOR
     *************************************************************************/
    protected function initDataConnector()
    {
        $dataConnector = new DataConnector();
        $dataConnector->setDataFolder(__DIR__ . '/../data');
        return $dataConnector;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
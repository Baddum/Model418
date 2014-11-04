<?php

namespace Test\Elephant418\Model418\Resources\SeparateCase;

use Elephant418\Model418\DataConnection\FileDataConnection as DataConnection;
use Elephant418\Model418\Entity;

class ResourceEntity extends Entity
{


    /* CONSTRUCTOR
     *************************************************************************/
    protected function initDataConnection()
    {
        $dataConnector = new DataConnection();
        $dataConnector->setDataFolder(__DIR__ . '/../data');
        return $dataConnector;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function test()
    {
        return $this->byId('test');
    }
}
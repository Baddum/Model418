<?php

namespace Test\Elephant418\Model418\Resources\SeparateCase;

use Elephant418\Model418\FileDataConnection as DataConnection;
use Elephant418\Model418\Entity;

class ResourceEntity extends Entity
{


    /* CONSTRUCTOR
     *************************************************************************/
    protected function initDataConnection()
    {
        $dataConnection = new DataConnection();
        $dataConnection->setDataFolder(__DIR__ . '/../data');
        return $dataConnection;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
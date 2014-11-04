<?php

namespace Test\Elephant418\Model418\Resources\SimpleCase;

use Elephant418\Model418\DataConnection\FileDataConnection as DataConnection;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initDataConnection()
    {
        $dataConnector = (new DataConnection)
            ->setDataFolder(__DIR__ . '/../data')
            ->setIdField('myName');
        return $dataConnector;
    }

    protected function initSchema()
    {
        return array('myName' => 'defaultValue');
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function test()
    {
        return $this->fetch()->byId('test');
    }
}
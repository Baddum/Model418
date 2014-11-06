<?php

namespace Test\Elephant418\Model418\Resources\JSONCase;

use Elephant418\Model418\FileDataConnection as DataConnection;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initDataConnection()
    {
        $dataConnection = (new DataConnection)
            ->setFileDataRequest('JSON')
            ->setDataFolder(__DIR__ . '/../data')
            ->setIdField('myName');
        return $dataConnection;
    }

    protected function initSchema()
    {
        return array(
            'myName' => 'defaultValue',
            'myArray' => 'defaultArray'
        );
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('json');
    }
}
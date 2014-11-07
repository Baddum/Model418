<?php

namespace Test\Elephant418\Model418\Resources\SubAttributeCase;

use Elephant418\Model418\FileDataConnection as DataConnection;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initDataConnection()
    {
        $dataConnection = (new DataConnection)
            ->setDataFolder(__DIR__ . '/../data')
            ->addSubAttribute('event')
            ->setIdField('myName');
        return $dataConnection;
    }

    protected function initSchema()
    {
        return array(
            'myName' => 'defaultValue',
            'event' => array()
        );
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
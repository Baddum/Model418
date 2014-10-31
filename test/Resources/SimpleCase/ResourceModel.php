<?php

namespace Test\Elephant418\Model418\Resources\SimpleCase;

use Elephant418\Model418\DataConnector\FileDataConnector as DataConnector;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initDataConnector()
    {
        $dataConnector = (new DataConnector)
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
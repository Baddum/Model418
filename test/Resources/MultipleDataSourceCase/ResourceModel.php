<?php

namespace Test\Elephant418\Model418\Resources\MultipleDataSourceCase;

use Elephant418\Model418\DataConnection\FileDataConnection as DataConnection;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initDataConnection()
    {
        $dataConnector = (new DataConnection)
            ->setDataFolderList([__DIR__ . '/../data', __DIR__ . '/../data2'])
            ->setIdField('myName');
        return $dataConnector;
    }

    protected function initSchema()
    {
        return array('myName' => 'defaultValue');
    }


    /* PUBLIC METHODS
     *************************************************************************/
    public function getWritableDataFolder() {
        return $this->getDataConnection()->getWritableDataFolder();
    }
}
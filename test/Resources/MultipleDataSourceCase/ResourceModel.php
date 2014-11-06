<?php

namespace Test\Elephant418\Model418\Resources\MultipleDataSourceCase;

use Elephant418\Model418\FileDataConnection as DataConnection;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initDataConnection()
    {
        $dataConnection = (new DataConnection)
            ->setDataFolderList([__DIR__ . '/../data', __DIR__ . '/../data2'])
            ->setIdField('myName');
        return $dataConnection;
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
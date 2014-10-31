<?php

namespace Test\Elephant418\Model418\Resources\MultipleDataSourceCase;

use Elephant418\Model418\DataConnector\FileDataConnector as DataConnector;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initDataConnector()
    {
        $dataConnector = (new DataConnector)
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
        return $this->getDataConnector()->getWritableDataFolder();
    }
}
<?php

namespace Test\Elephant418\Model418\Resources\YamlCase;

use Elephant418\Model418\DataConnection\FileDataConnection as DataConnection;
use Elephant418\Model418\DataConnection\YamlFileDataRequest;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initDataConnection()
    {
        $dataConnection = (new DataConnection)
            ->setFileDataRequest('Yaml')
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
    public function fetchYaml()
    {
        return $this->fetchById('yaml');
    }
}
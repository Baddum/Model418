<?php

namespace Test\Elephant418\Model418\Resources\MdAttributeCase;

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
            ->setSubAttribute('content', 'article', 'md')
            ->setIdField('myName');
        return $dataConnection;
    }

    protected function initSchema()
    {
        return array(
            'myName' => 'defaultValue',
            'content' => ''
        );
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
<?php

namespace Baddum\Model418\Test\Resources\Simple;

use Baddum\Model418\FileProvider as Provider;
use Baddum\Model418\ModelQuery;

class ResourceModel extends ModelQuery
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initProvider()
    {
        $provider = (new Provider)
            ->setFolder(__DIR__ . '/../data')
            ->setIdField('myName');
        return $provider;
    }

    protected function initSchema()
    {
        return array('myName' => 'defaultValue');
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
<?php

namespace Test\Elephant418\Model418\Resources\MdAttributeCase;

use Elephant418\Model418\FileProvider as Provider;
use Elephant418\Model418\ModelEntity;

class ResourceModel extends ModelEntity
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initProvider()
    {
        $provider = (new Provider)
            ->setFolder(__DIR__ . '/../data')
            ->setSubAttribute('content', 'article', 'md')
            ->setIdField('myName');
        return $provider;
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
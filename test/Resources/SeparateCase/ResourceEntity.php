<?php

namespace Test\Elephant418\Model418\Resources\SeparateCase;

use Elephant418\Model418\FileProvider as Provider;
use Elephant418\Model418\Entity;

class ResourceEntity extends Entity
{


    /* CONSTRUCTOR
     *************************************************************************/
    protected function initProvider()
    {
        $provider = (new Provider)
            ->setFolder(__DIR__ . '/../data');
        return $provider;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchTest()
    {
        return $this->fetchById('test');
    }
}
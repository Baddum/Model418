<?php

namespace Baddum\Model418\Test\Resources\Separate;

use Baddum\Model418\FileProvider as Provider;
use Baddum\Model418\Query;

class ResourceQuery extends Query
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
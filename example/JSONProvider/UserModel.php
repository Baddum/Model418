<?php

namespace Baddum\Model418\Example\JSONProvider;

use Baddum\Model418\ModelQuery;
use Baddum\Model418\FileProvider as Provider;

class UserModel extends ModelQuery
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initProvider()
    {
        $provider = (new Provider)
            ->setRequest('JSON')
            ->setFolder(__DIR__ . '/User')
            ->setIdField('myName');
        return $provider;
    }
}
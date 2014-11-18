<?php

namespace Model418\Example\JSONProvider;

use Model418\ModelQuery;
use Model418\FileProvider as Provider;

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
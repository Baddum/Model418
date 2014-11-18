<?php

namespace Model418\Example\SessionProvider;

use Model418\ModelQuery;
use Model418\SessionProvider as Provider;

class UserModel extends ModelQuery
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initProvider()
    {
        $provider = (new Provider)
            ->setKey('User')
            ->setIdField('myName');
        return $provider;
    }
}
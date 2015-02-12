<?php

namespace Baddum\Model418\Example\SessionProvider;

use Baddum\Model418\ModelQuery;
use Baddum\Model418\SessionProvider as Provider;

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
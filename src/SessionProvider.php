<?php

namespace Elephant418\Model418;

use Elephant418\Model418\Core\Provider\IProvider;
use Elephant418\Model418\Core\Provider\AspectProvider\TNamedIdAspectProvider;
use Elephant418\Model418\Core\Provider\AspectProvider\KeyValueAspectProvider;
use Elephant418\Model418\Core\Request\SessionRequest;

class SessionProvider extends KeyValueAspectProvider implements IProvider
{
    use TNamedIdAspectProvider;

    protected function initDefaultRequest()
    {
        return new SessionRequest;
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function isIdAvailable($id)
    {
        return parent::isIdAvailable($id);
    }
}
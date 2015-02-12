<?php

namespace Baddum\Model418;

use Baddum\Model418\Core\Provider\IProvider;
use Baddum\Model418\Core\Provider\AspectProvider\TNamedIdAspectProvider;
use Baddum\Model418\Core\Provider\AspectProvider\KeyValueAspectProvider;
use Baddum\Model418\Core\Request\SessionRequest;

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
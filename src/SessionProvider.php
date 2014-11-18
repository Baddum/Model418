<?php

namespace Model418;

use Model418\Core\Provider\NoRelationProvider;
use Model418\Core\Provider\IProvider;
use Model418\Core\Provider\TNamedIdProvider;
use Model418\Core\Request\SessionRequest;

class SessionProvider extends NoRelationProvider implements IProvider
{
    use TNamedIdProvider;

    protected function initDefaultRequest()
    {
        return new SessionRequest;
    }
}
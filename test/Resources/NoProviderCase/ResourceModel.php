<?php

namespace Test\Elephant418\Model418\Resources\NoProviderCase;

use Elephant418\Model418\ModelQuery;

class ResourceModel extends ModelQuery
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initSchema()
    {
        return array('myName' => 'defaultValue');
    }
}
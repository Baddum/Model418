<?php

namespace Baddum\Model418\Test\Resources\NoQuery;

use Baddum\Model418\Model;

class ResourceModel extends Model
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initSchema()
    {
        return array('myName' => 'defaultValue');
    }
}
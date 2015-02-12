<?php

namespace Baddum\Model418\Test\Resources\Separate;

use Baddum\Model418\Model;

class ResourceModel extends Model
{


    /* INITIALIZATION
     *************************************************************************/
    protected function initSchema()
    {
        return array('myName' => 'defaultValue');
    }
    
    protected function initQuery()
    {
        return new ResourceQuery($this);
    }
}
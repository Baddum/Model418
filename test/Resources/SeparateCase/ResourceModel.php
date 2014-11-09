<?php

namespace Test\Elephant418\Model418\Resources\SeparateCase;

use Elephant418\Model418\Model;

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
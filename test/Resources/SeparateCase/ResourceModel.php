<?php

namespace Test\Elephant418\Model418\Resources\SeparateCase;

use Elephant418\Model418\Model;

class ResourceModel extends Model
{
    use ResourceEntity;


    /* ATTRIBUTES
     *************************************************************************/
    protected $_schema = array('myName' => 'defaultValue');
}
<?php

namespace Test\Elephant418\Packy\Resources\SeparateCase;

use Elephant418\Packy\Model;

class ResourceModel extends Model
{
    use ResourceEntity;


    /* ATTRIBUTES
     *************************************************************************/
    protected $_schema = array('myName' => 'defaultValue');
}
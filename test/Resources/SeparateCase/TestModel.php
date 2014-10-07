<?php

namespace Test\Elephant418\Packy\Resources\SeparateCase;

use Elephant418\Packy\Model;

class TestModel extends Model
{
    use TestEntity;
    
    
    /* ATTRIBUTES
     *************************************************************************/
    protected $_schema = array('myName' => 'defaultValue');
}
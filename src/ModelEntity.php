<?php

namespace Elephant418\Model418;

use Elephant418\Model418\Core\IEntity;
use Elephant418\Model418\Core\TEntity;

class ModelEntity extends Model implements IEntity
{
    use TEntity;


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($dataConnection = null)
    {
        parent::__construct();
        $this->_modelClass = get_class($this);
        $this->injectDataConnection($dataConnection);
    }


    /* PROTECTED ENTITY METHODS
     *************************************************************************/
    protected function initEntity()
    {
        return $this;
    }
}
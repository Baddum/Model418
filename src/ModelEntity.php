<?php

namespace Elephant418\Model418;

class ModelEntity extends Model implements IEntity
{
    use TEntity;


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($dataConnector = null)
    {
        parent::__construct();
        $this->_modelClass = get_class($this);
        $this->injectDataConnection($dataConnector);
    }


    /* PROTECTED ENTITY METHODS
     *************************************************************************/
    protected function initEntity()
    {
        return $this;
    }
}
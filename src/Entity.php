<?php

namespace Elephant418\Model418;

class Entity implements IEntity
{
    use TEntity;


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($model, $dataConnector = null)
    {
        $this->_modelClass = get_class($model);
        $this->injectDataConnection($dataConnector);
    }
}
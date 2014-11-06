<?php

namespace Elephant418\Model418;

use Elephant418\Model418\Core\IEntity;
use Elephant418\Model418\Core\TEntity;

class Entity implements IEntity
{
    use TEntity;


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($model, $dataConnection = null)
    {
        $this->_modelClass = get_class($model);
        $this->injectDataConnection($dataConnection);
    }
}
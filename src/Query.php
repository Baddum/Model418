<?php

namespace Elephant418\Model418;

use Elephant418\Model418\Core\IQuery;
use Elephant418\Model418\Core\TQuery;

class Query implements IQuery
{
    use TQuery;


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($model, $provider = null)
    {
        $this->_modelClass = get_class($model);
        $this->injectProvider($provider);
    }
}
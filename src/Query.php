<?php

namespace Baddum\Model418;

use Baddum\Model418\Core\Query\IQuery;
use Baddum\Model418\Core\Query\TQuery;

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
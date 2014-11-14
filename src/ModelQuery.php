<?php

namespace Model418;

use Model418\Core\Query\IQuery;
use Model418\Core\Query\TQuery;

class ModelQuery extends Model implements IQuery
{
    use TQuery;


    /* INITIALIZATION
     *************************************************************************/
    public function __construct($provider = null)
    {
        parent::__construct();
        $this->_modelClass = get_called_class();
        $this->injectProvider($provider);
    }


    /* PROTECTED ENTITY METHODS
     *************************************************************************/
    protected function initQuery()
    {
        return $this;
    }

    protected function initProvider()
    {
        $folder = $this->initFolder();
        if (!file_exists($folder)) {
            mkdir($folder, 0777, true);
        }
        $provider = (new FileProvider)
            ->setFolder($folder);
        return $provider;
    }

    protected function initFolder()
    {
        $className = get_class($this);
        $folder = './data/'.substr($className, strrpos($className, '\\') + 1);
        return $folder;
    }
}
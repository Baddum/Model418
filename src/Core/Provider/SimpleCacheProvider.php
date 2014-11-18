<?php

namespace Model418\Core\Provider;

abstract class SimpleCacheProvider extends NoRelationProvider
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $_cache = array();


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchAll($limit = null, $offset = null, &$count = false)
    {
        if ($this->hasCache()) {
            $dataList = $this->getCache();
            $dataList = $this->slice($dataList, $limit, $offset);
        } else {
            $dataList = parent::fetchAll($limit, $offset, $count);
            if (is_null($limit)) {
                $this->setCache($dataList);
            }
        }
        return $dataList;
    }


    /* CACHE METHODS
     *************************************************************************/
    public function clearCache()
    {
        $this->_cache[get_class($this)] = null;
        return $this;
    }
    
    protected function setCache($dataList)
    {
        $this->_cache[get_class($this)] = $dataList;
        return $this;
    }

    protected function hasCache()
    {
        return isset($this->_cache[get_class($this)]);
    }

    protected function getCache()
    {
        if ($this->hasCache()) {
            return $this->_cache[get_class($this)];
        }
        return array();
    }
    
    
    /* PROTECTED METHODS
     *************************************************************************/
    protected function getAllIds()
    {
        throw new \LogicException('This method must be overridden');
    }
}
<?php

namespace Elephant418\Model418\Core\Provider;

abstract class CacheDumpProvider extends NoRelationProvider
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $_cache = array();


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        if ($this->hasCache()) {
            $data = $this->getCacheItem($id);
            return $data;
        }
        return parent::fetchById($id);
    }
    
    public function fetchAll($limit = null, $offset = null, &$count = false)
    {
        if ($this->hasCache()) {
            $dataList = $this->getCache();
            return $this->slice($dataList, $limit, $offset);
        }
        $dataList = parent::fetchAll($limit, $offset, $count);
        if (is_null($limit)) {
            $this->setCache($dataList);
        }
        return $dataList;
    }


    /* SAVE METHODS
     *************************************************************************/
    public function saveById($id, $data)
    {
        $id = parent::saveById($id, $data);
        $this->setCacheItem($id, $data);
        return $id;
    }

    public function deleteById($id)
    {
        $status = parent::deleteById($id);
        $this->clearCacheItem($id);
        return $status;
    }


    /* CACHE METHODS
     *************************************************************************/
    public function clearCache()
    {
        $this->_cache[get_class($this)] = null;
        return $this;
    }
    
    public function clearCacheItem($id)
    {
        if ($this->hasCache()) {
            $this->_cache[get_class($this)][$id] = null;
        }
        return $this;
    }
    
    protected function setCache($dataList)
    {
        $this->_cache[get_class($this)] = $dataList;
        return $this;
    }

    protected function setCacheItem($id, $data)
    {
        if ($this->hasCache()) {
            $data['id'] = $id;
            $this->_cache[get_class($this)][$id] = $data;
        }
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

    protected function getCacheItem($id)
    {
        if (!$this->hasCache()) {
            return array();
        }
        if (!isset($this->_cache[get_class($this)][$id])) {
            return array();
        }
        return $this->_cache[get_class($this)][$id];
    }
}
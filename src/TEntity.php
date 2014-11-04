<?php

namespace Elephant418\Model418;

trait TEntity
{


    /* ATTRIBUTES
     *************************************************************************/
    protected static $_dataConnector = array();
    protected $_modelClass;


    /* FETCHING METHODS
     *************************************************************************/
    public function byId($id)
    {
        $data = $this->getDataConnection()->fetchById($id);
        return $this->resultAsModel($data);
    }

    public function byIdList($idList)
    {
        $dataList = $this->getDataConnection()->fetchByIdList($idList);
        return $this->resultAsModelList($dataList);
    }

    public function all($limit = null, $offset = null, &$count = false)
    {
        $dataList = $this->getDataConnection()->fetchAll($limit, $offset, $count);
        return $this->resultAsModelList($dataList);
    }

    public function saveById($id, $data)
    {
        return $this->getDataConnection()->saveById($id, $data);
    }

    public function deleteById($id)
    {
        return $this->getDataConnection()->deleteById($id);
    }


    /* PROTECTED DATA CONNECTOR ACCESSOR
     *************************************************************************/
    protected function initDataConnection()
    {
        throw new \LogicException('This method must be overridden');
    }

    protected function injectDataConnection($dataConnector)
    {
        if (!$this->hasDataConnection() && !$dataConnector) {
            $dataConnector = $this->initDataConnection();
        }
        if ($dataConnector) {
            $this->setDataConnection($dataConnector);
        }
    }

    protected function hasDataConnection()
    {
        return isset(static::$_dataConnector[get_class($this)]);
    }

    protected function setDataConnection($dataConnector)
    {
        static::$_dataConnector[get_class($this)] = $dataConnector;
        return $this;
    }

    protected function getDataConnection()
    {
        if (!$this->hasDataConnection()) {
            return null;
        }
        return static::$_dataConnector[get_class($this)];
    }


    /* PROTECTED MODEL METHODS
     *************************************************************************/
    protected function getModel()
    {
        $class = $this->_modelClass;
        return new $class;
    }

    protected function resultAsModelList($dataList)
    {
        $modelList = array();
        foreach ($dataList as $data) {
            $model = $this->getModel();
            $modelList[$model->id] = $model->initByData($data);
        }
        return $modelList;
    }

    protected function resultAsModel($data)
    {
        return $this->getModel()->initByData($data);
    }

}
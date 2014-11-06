<?php

namespace Elephant418\Model418\Core;

trait TEntity
{


    /* ATTRIBUTES
     *************************************************************************/
    protected static $_dataConnection = array();
    protected $_modelClass;


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        $data = $this->getDataConnection()->fetchById($id);
        return $this->resultAsModel($data);
    }

    public function fetchByIdList($idList)
    {
        $dataList = $this->getDataConnection()->fetchByIdList($idList);
        return $this->resultAsModelList($dataList);
    }

    public function fetchAll($limit = null, $offset = null, &$count = false)
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

    protected function injectDataConnection($dataConnection)
    {
        if (!$this->hasDataConnection() && !$dataConnection) {
            $dataConnection = $this->initDataConnection();
        }
        if ($dataConnection) {
            $this->setDataConnection($dataConnection);
        }
    }

    protected function hasDataConnection()
    {
        return isset(static::$_dataConnection[get_class($this)]);
    }

    protected function setDataConnection($dataConnection)
    {
        static::$_dataConnection[get_class($this)] = $dataConnection;
        return $this;
    }

    protected function getDataConnection()
    {
        if (!$this->hasDataConnection()) {
            return null;
        }
        return static::$_dataConnection[get_class($this)];
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
        $model = $this->getModel();
        if ($data) {
            $model->initByData($data);
        }
        return $model;
    }

}
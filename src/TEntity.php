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
        $data = $this->getDataConnector()->fetchById($id);
        return $this->resultAsModel($data);
    }

    public function byIdList($idList)
    {
        $dataList = $this->getDataConnector()->fetchByIdList($idList);
        return $this->resultAsModelList($dataList);
    }

    public function all($limit = null, $offset = null, &$count = false)
    {
        $dataList = $this->getDataConnector()->fetchAll($limit, $offset, $count);
        return $this->resultAsModelList($dataList);
    }

    public function saveById($id, $data)
    {
        return $this->getDataConnector()->saveById($id, $data);
    }

    public function deleteById($id)
    {
        return $this->getDataConnector()->deleteById($id);
    }


    /* PROTECTED DATA CONNECTOR ACCESSOR
     *************************************************************************/
    protected function initDataConnector()
    {
        throw new \LogicException('This method must be overridden');
    }

    protected function injectDataConnector($dataConnector)
    {
        if (!$this->hasDataConnector() && !$dataConnector) {
            $dataConnector = $this->initDataConnector();
        }
        if ($dataConnector) {
            $this->setDataConnector($dataConnector);
        }
    }

    protected function hasDataConnector()
    {
        return isset(static::$_dataConnector[get_class($this)]);
    }

    protected function setDataConnector($dataConnector)
    {
        static::$_dataConnector[get_class($this)] = $dataConnector;
        return $this;
    }

    protected function getDataConnector()
    {
        if (!$this->hasDataConnector()) {
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
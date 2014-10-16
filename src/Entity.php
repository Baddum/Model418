<?php

namespace Elephant418\Model418;

trait Entity
{


    /* ATTRIBUTES
     *************************************************************************/
    protected static $_dataConnector;
    protected $_model = 'Elephant418\\Model418\\Model';


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        $data = $this->getDataConnector()->fetchById($id);
        return $this->resultAsModel($data);
    }

    public function fetchByIdList($idList)
    {
        $dataList = $this->getDataConnector()->fetchByIdList($idList);
        return $this->resultAsModelList($dataList);
    }

    public function fetchAll($limit = null, $offset = null, &$count = false)
    {
        $dataList = $this->getDataConnector()->fetchAll($limit, $offset, $count);
        return $this->resultAsModelList($dataList);
    }


    /* SAVE METHODS
     *************************************************************************/
    public function save()
    {
        $id = $this->getDataConnector()->save($this->id, $this->toArray());
        if (is_null($this->id)) {
            $this->id = $id;
        }
    }

    public function delete()
    {
        if (!is_null($this->id)) {
            $this->getDataConnector()->delete($this->id);
        }
    }


    /* PRIVATE DATA CONNECTOR ACCESSOR
     *************************************************************************/
    protected function initDataConnector()
    {
        throw new \LogicException('This method must be overridden');
    }

    protected function getDataConnector()
    {
        return self::$_dataConnector;
    }

    protected function setDataConnector($dataConnector)
    {
        self::$_dataConnector = $dataConnector;
    }


    /* PRIVATE MODEL METHODS
     *************************************************************************/
    protected function getModel()
    {
        $class = get_class($this);
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
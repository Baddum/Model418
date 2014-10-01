<?php

namespace Elephant418\Packy;

trait Entity
{


    /* ATTRIBUTES
     *************************************************************************/
    protected static $_dataConnector;
    


    /* CONSTRUCTOR
     *************************************************************************/
    public function initialize()
    {
        $this->setDataConnector(new DataConnector());
    }

    public function getDataConnector()
    {
        if (! self::$_dataConnector) {
            $this->setDataConnector(new DataConnector);
        }
        return self::$_dataConnector;
    }
    
    public function setDataConnector($dataConnector)
    {
        self::$_dataConnector = $dataConnector;
    }


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
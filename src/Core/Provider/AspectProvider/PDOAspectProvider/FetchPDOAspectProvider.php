<?php

namespace Baddum\Model418\Core\Provider\AspectProvider\PDOAspectProvider;

use Baddum\Model418\Core\Provider\IProvider;
use SQLBuilder\Driver as SQLDriver;
use SQLBuilder\QueryBuilder as FetchBuilder;
use Baddum\Model418\Core\Request\PDORequest as Request;

abstract class FetchPDOAspectProvider extends PDOAspectProvider implements IProvider
{

    /* ATTRIBUTES
     *************************************************************************/
    protected $baseFetch;


    /* SETTER
     *************************************************************************/
    public function setBaseFetch($baseFetch)
    {
        if (is_callable($baseFetch)) {
            $baseFetch = $baseFetch($this->getBaseFetch(), $this);
        }
        $this->baseFetch = $baseFetch;
        return $this;
    }

    public function getBaseFetch()
    {
        if (is_null($this->baseFetch)) {
            $driver = $this->PDO->getAttribute(\PDO::ATTR_DRIVER_NAME);
            $driver = new SQLDriver($driver);
            $baseFetch = (new FetchBuilder($driver))
                ->table($this->table)
                ->select($this->table . '.*');
            $this->baseFetch = $baseFetch;
        }
        $baseFetch = clone $this->baseFetch;
        return $baseFetch;
    }


    /* FETCHING LIST METHODS
     *************************************************************************/
    public function fetchByField($field, $value, $limit = null, $order = null, $offset = null, &$count = false)
    {
        $fetch = $this->getBaseFetch()
            ->where()
            ->equal($field, ':' . $field);
        $parameters = array(':' . $field => $value);
        return $this->fetch($fetch, $parameters, $limit, $order, $offset, $count);
    }

    public function fetchByIdList($ids, $limit = null, $order = null, $offset = null, &$count = false)
    {
        if (empty($ids)) {
            return array();
        }
        $fetch = $this->getBaseFetch()
            ->where()
            ->in($this->idField, ':' . $ids);
        $parameters = array();
        return $this->fetch($fetch, $parameters, $limit, $order, $offset, $count);
    }

    public function fetchAll($limit = null, $order = null, $offset = null, &$count = false)
    {
        $fetch = $this->getBaseFetch();
        $parameters = array();
        return $this->fetch($fetch, $parameters, $limit, $order, $offset, $count);
    }
    
    public function fetch($fetch, $parameters = array(), $limit = null, $order = null, $offset = null, &$count = false)
    {
        $this->setFetchOptions($fetch, $limit, $order, $offset, $count);
        $SQL = $fetch->build();
        if (!is_null($count)) {
            $SQL = 'SELECT SQL_CALC_FOUND_ROWS'.substr($SQL, 6);
        }
        $dataList = $this->execute($SQL, $parameters, $count);
        if ($count !== false) {
            $count = $this->fetchCount();
        }
        if (is_array($dataList)) {
            foreach ($dataList as $key => $data) {
                $dataList[$key] = $this->getDataWithIdField($data);
            }
        }
        return $dataList;
    }


    /* FETCHING ONE METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        return $this->fetchOneByField($this->idField, $id);
    }

    public function fetchOneByField($field, $value)
    {
        $fetch = $this->getBaseFetch()
            ->where()
            ->equal($field, ':' . $field);
        $parameters = array(':' . $field => $value);
        return $this->fetchOne($fetch, $parameters);
    }

    public function fetchOne($fetch, $parameters = array())
    {
        $data = $this->execute($fetch->build(), $parameters);
        return $this->getOneData($data);
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function setFetchOptions(&$fetch, $limit, $order, $offset)
    {
        if (!is_null($limit)) {
            $fetch->limit($limit);
        }
        if (!is_null($offset)) {
            $fetch->offset($offset);
        }
        if (!is_null($order)) {
            $fetch->clearOrderBy($order);
            if (is_array($order)) {
                $fetch->order($order[0], $order[1]);
            } else {
                $fetch->order($order);
            }
        }
        return $fetch;
    }
    
    protected function fetchCount()
    {
        $parameters = [];
        $SQL = 'SELECT FOUND_ROWS();';
        $request = new Request($this->PDO, $SQL);
        $data = $request->executeOne($parameters);
        return reset($data);
    }

    protected function getOneData($data)
    {
        if (is_array($data) && count($data) > 0) {
            $data = $this->getDataWithIdField($data[0]);
        }
        return $data;
    }

    protected function getDataWithIdField($data)
    {
        if (isset($data[$this->idField])) {
            $data['id'] = $data[$this->idField];
            unset($data[$this->idField]);
        }
        return $data;
    }
}

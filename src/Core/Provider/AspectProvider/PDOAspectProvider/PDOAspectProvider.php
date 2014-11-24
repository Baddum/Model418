<?php

namespace Elephant418\Model418\Core\Provider\AspectProvider\PDOAspectProvider;

use Elephant418\Model418\Core\Request\PDORequest as Request;

abstract class PDOAspectProvider
{

    /* ATTRIBUTES
     *************************************************************************/
    protected $table;
    protected $idField = 'id';
    protected $PDO;


    /* GETTER & SETTER
     *************************************************************************/
    public function setIdField($idField)
    {
        $this->idField = $idField;
        return $this;
    }

    public function getIdField()
    {
        return $this->idField;
    }

    public function setTable($table)
    {
        $this->table = $table;
        return $this;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function setPDO($PDO)
    {
        $this->PDO = $PDO;
        return $this;
    }


    /* PUBLIC FETCHING METHODS
     *************************************************************************/
    public function execute(&$SQL, $parameters = array(), &$count = false)
    {
        if (is_array($SQL)) {
            $SQL = implode(' ', $SQL);
        }
        echo $SQL.PHP_EOL;
        if (is_string($SQL)) {
            $SQL = new Request($this->PDO, $SQL);
        }
        $result = $SQL->execute($parameters);
        return $result;
    }

    public function deleteById($id)
    {
        if (is_null($id)) {
            return true;
        }
        $SQL = array(
            'DELETE FROM ' . $this->table,
            'WHERE ' . $this->idField . '=:id'
        );
        $parameters = [];
        $parameters[':id'] = $id;
        return $this->execute(implode(' ', $SQL), $parameters);
    }

    public function saveById($id, $data)
    {
        if (is_null($id)) {
            return $this->insertById($id, $data);
        }
        return $this->updateById($id, $data);
    }


    /* PRIVATE SAVE METHODS
     *************************************************************************/
    protected function insertById($id, $data)
    {
        $fieldList = array_keys($data);
        $SQL = array(
            'INSERT INTO ' . $this->table,
            '(`' . implode('`, `', $fieldList) . '`) VALUES',
            '(:' . implode(', :', $fieldList) . ');'
        );
        $this->execute($SQL, $this->getBindParams($id, $data));
        return $SQL->getLastInsertId();
    }

    public function updateById($id, $data)
    {
        $SQL = array(
            'UPDATE ' . $this->table,
            'SET ' . $this->getSetRequest($data),
            'WHERE `' . $this->idField . '` = :' . $this->idField . ' ;'
        );
        $this->execute($SQL, $this->getBindParams($id, $data));
        return $id;
    }


    /* PRIVATE SQL METHODS
     *************************************************************************/
    protected function getSetRequest($data)
    {
        $request = [];
        $fieldList = array_keys($data);
        foreach ($fieldList as $fieldName) {
            if ($fieldName != $this->idField) {
                $request[] = '`' . $fieldName . '` = :' . $fieldName;
            }
        }
        return implode(', ', $request);
    }

    protected function getBindParams($id, $data)
    {
        $bindParams = [];
        $fieldList = array_keys($data);
        foreach ($fieldList as $fieldName) {
            $fieldValue = null;
            if (isset($data[$fieldName])) {
                $fieldValue = $data[$fieldName];
            }
            $bindParams[':'.$fieldName] = $fieldValue;
        }
        if (!is_null($id)) {
            $bindParams[$this->idField] = $id;
        }
        return $bindParams;
    }
}

<?php

namespace Elephant418\Packy;

class DataConnector
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $dataFolder;


    /* CONSTRUCTOR
     *************************************************************************/
    public function __construct()
    {
    }
    
    public function setIdField($idField) {
        $this->idField = $idField;
    }

    public function setDataFolder($dataFolder) {
        $this->dataFolder = $dataFolder;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        $sourceFile = $this->getSourceFileById($id);
        if (!file_exists($sourceFile)) {
            return null;
        }
        return $this->getDataFromSourceFile($id, $sourceFile);
    }

    public function fetchByIdList($idList)
    {
        $dataList = array();
        foreach ($idList as $id) {
            $data = $this->fetchById($id);
            if ($data) {
                $dataList[$id] = $data;
            }
        }
        return $dataList;
    }

    public function fetchAll($limit = null, $offset = null, &$count = false)
    {
        $idList = $this->getAllIds();
        if ($count !== false) {
            $count = count($idList);
        }
        if (is_null($offset)) {
            $offset = 0;
        }
        if (! is_null($limit)) {
            $idList = array_slice($idList, $offset, $offset + $limit);
        }
        return $this->fetchByIdList($idList);
    }


    /* PROTECTED SOURCE FILE METHODS
     *************************************************************************/
    protected function getDataFromSourceFile($id, $filePath)
    {
        $data = array();
        $data['id'] = $id;
        $jsonData = json_decode(file_get_contents($filePath), true);
        if (is_array($jsonData)) {
            $data = array_merge($data, $jsonData);
        }
        return $data;
    }

    protected function getSourceFileById($id)
    {
        return $this->dataFolder.'/'.$id.'.json';
    }

    protected function getAllIds()
    {
        $idList = array();
        foreach (glob($this->dataFolder.'/*.json') as $file) {
            $file = basename($file);
            $id = substr($file, 0, strrpos($file, '.'));
            $idList[] = $id;
        }
        return $idList;
    }


}
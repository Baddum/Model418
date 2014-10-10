<?php

namespace Elephant418\Packy\DataConnector;

class FileDataConnector
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $dataFolder;


    /* SETTER
     *************************************************************************/
    public function setDataFolder($dataFolder)
    {
        $this->dataFolder = $dataFolder;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        $sourceFileName = $this->getSourceFileNameById($id);
        $sourceText = (new FileRequest)->getContents($sourceFileName);
        return $this->getDataFromText($id, $sourceText);
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
        if (!is_null($limit)) {
            $idList = array_slice($idList, $offset, $offset + $limit);
        }
        return $this->fetchByIdList($idList);
    }


    /* PROTECTED SOURCE FILE METHODS
     *************************************************************************/
    protected function getDataFromText($id, $fileText)
    {
        $data = json_decode($fileText, true);
        if (!is_array($data)) {
            return null;
        }
        $data['id'] = $id;
        return $data;
    }

    protected function getSourceFileNameById($id)
    {
        return $this->dataFolder . '/' . $id . '.json';
    }

    protected function getAllIds()
    {
        $idList = array();
        $fileList = (new FileRequest)->getList($this->dataFolder . '/*.json');
        foreach ($fileList as $file) {
            $file = basename($file);
            $id = substr($file, 0, strrpos($file, '.'));
            $idList[] = $id;
        }
        return $idList;
    }


}
<?php

namespace Elephant418\Packy\DataConnector;

use Elephant418\Packy\IDataConnector;

class FileDataConnector implements IDataConnector
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $fileRequest;
    protected $idField = 'name';
    protected $dataFolder;


    /* SETTER
     *************************************************************************/
    public function setDataFolder($dataFolder)
    {
        $realDataFolder = realpath($dataFolder);
        if (!$realDataFolder) {
            throw new \RuntimeException('This data folder does not exist: ' . $dataFolder);
        }
        $this->dataFolder = $realDataFolder;
        return $this;
    }

    public function getDataFolder()
    {
        return $this->dataFolder;
    }

    public function setIdField($idField)
    {
        $this->idField = $idField;
        return $this;
    }


    /* CONSTRUCTOR
     *************************************************************************/
    public function __construct($fileRequest = null)
    {
        if (!$fileRequest) {
            $fileRequest = new FileRequest;
        }
        $this->fileRequest = $fileRequest;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        $sourceFileName = $this->getSourceFileNameById($id);
        $sourceText = $this->fileRequest->getContents($sourceFileName);
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
            $idList = array_slice($idList, $offset, $limit);
        }
        return $this->fetchByIdList($idList);
    }


    /* SAVE METHODS
     *************************************************************************/
    public function save($id, $data)
    {
        $exists = !is_null($id);
        if (!$exists) {
            $name = '';
            $suffix = 0;
            if (isset($data[$this->idField])) {
                $name = $data[$this->idField];
            }
            do {
                $id = $this->getIdByNameAndSuffix($name, $suffix);
                $sourceFileName = $this->getSourceFileNameById($id);
                $suffix++;
            } while ($this->fileRequest->exists($sourceFileName));
        } else {
            $sourceFileName = $this->getSourceFileNameById($id);
        }
        $this->fileRequest->putContents($sourceFileName, json_encode($data));
        return $id;
    }

    public function delete($id)
    {
        $sourceFileName = $this->getSourceFileNameById($id);
        return $this->fileRequest->unlink($sourceFileName);
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

    protected function getIdByNameAndSuffix($name = '', $suffix = 0)
    {
        $id = '';
        if (!empty($name)) {
            $id = $name;
        } else if (!$suffix) {
            $suffix = 1;
        }
        if ($suffix) {
            if (!empty($id)) {
                $id .= '-';
            }
            $id .= $suffix;
        }
        return $id;
    }

    protected function getAllIds()
    {
        $idList = array();
        $fileList = $this->fileRequest->getList($this->dataFolder . '/*.json');
        foreach ($fileList as $file) {
            $file = basename($file);
            $id = substr($file, 0, strrpos($file, '.'));
            $idList[] = $id;
        }
        return $idList;
    }


}
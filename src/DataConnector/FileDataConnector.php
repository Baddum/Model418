<?php

namespace Elephant418\Model418\DataConnector;

use Elephant418\Model418\IDataConnector;

class FileDataConnector implements IDataConnector
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $fileRequest;
    protected $idField = 'name';
    protected $dataFolderList = array();


    /* SETTER
     *************************************************************************/
    public function setDataFolder($dataFolder)
    {
        return $this->setDataFolderList(array($dataFolder));
    }
    
    public function setDataFolderList($dataFolderList)
    {
        $validDataFolderList = array();
        foreach ($dataFolderList as $dataFolder) {
            $validDataFolderList[] = $this->validDataFolder($dataFolder);
        }
        $this->dataFolderList = $validDataFolderList;
        return $this;
    }

    protected function validDataFolder($dataFolder)
    {
        $realDataFolder = realpath($dataFolder);
        if (!$realDataFolder) {
            throw new \RuntimeException('This data folder does not exist: ' . $dataFolder);
        }
        return $realDataFolder;
    }

    public function getWritableDataFolder()
    {
        return array_values($this->dataFolderList)[0];
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
        foreach ($this->dataFolderList as $dataFolder) {
            $filePath = $this->getSourceFilePath($dataFolder, $id);
            $sourceText = $this->fileRequest->getContents($filePath);
            if ($sourceText) {
                return $this->getDataFromText($id, $sourceText);
            }
        }
        return null;
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
    public function saveById($id, $data)
    {
        $exists = !is_null($id);
        if (!$exists) {
            $name = '';
            if (isset($data[$this->idField])) {
                $name = $data[$this->idField];
            }
            $id = $this->findAvailableIdByName($name);
        }
        $filePath = $this->getWritableFilePathById($id);
        $this->fileRequest->putContents($filePath, json_encode($data));
        return $id;
    }

    public function deleteById($id)
    {
        $filePath = $this->getWritableFilePathById($id);
        return $this->fileRequest->unlink($filePath);
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

    protected function getSourceFilePath($dataFolder, $id)
    {
        return $dataFolder . '/' . $id . '.json';
    }

    protected function getWritableFilePathById($id)
    {
        return $this->getSourceFilePath($this->getWritableDataFolder(), $id);
    }
    
    protected function findAvailableIdByName($name) {
        $suffix = 0;
        do {
            $id = $this->getIdByNameAndSuffix($name, $suffix);
            $filePath = $this->getWritableFilePathById($id);
            $suffix++;
        } while ($this->fileRequest->exists($filePath));
        return $id;
    }

    protected function getIdByNameAndSuffix($name = '', $suffix = 0)
    {
        $id = array();
        if (!empty($name)) {
            $id[] = $name;
        }
        if ($suffix > 0 || empty($id)) {
            $id[] = $suffix+1;
        }
        return implode('-', $id);
    }

    protected function getAllIds()
    {
        $idList = array();
        foreach ($this->dataFolderList as $dataFolder) {
            $fileList = $this->fileRequest->getList($dataFolder . '/*.json');
            foreach ($fileList as $file) {
                $file = basename($file);
                $id = substr($file, 0, strrpos($file, '.'));
                $idList[] = $id;
            }
        }
        return array_unique($idList);
    }


}
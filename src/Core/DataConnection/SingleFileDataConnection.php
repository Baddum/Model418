<?php

namespace Elephant418\Model418\Core\DataConnection;

use Elephant418\Model418\Core\DataConnection\FileDataRequest\FileDataRequestFactory;
use Elephant418\Model418\Core\DataConnection\FileDataRequest\JSONFileDataRequest;
use Elephant418\Model418\Core\DataConnection\FileDataRequest\YamlFileDataRequest;
use Elephant418\Model418\Core\DataConnection\FileDataRequest\MarkdownFileDataRequest;

JSONFileDataRequest::register();
YamlFileDataRequest::register();
MarkdownFileDataRequest::register();

class SingleFileDataConnection implements IDataConnection
{
    use TNoRelationDataConnection;


    /* ATTRIBUTES
     *************************************************************************/
    protected $fileDataRequest;
    protected $dataFolder;
    protected $idField = 'name';


    /* SETTER
     *************************************************************************/
    public function setIdField($idField)
    {
        $this->idField = $idField;
        return $this;
    }


    /* DATA FOLDER METHODS
     *************************************************************************/
    public function getDataFolder()
    {
        return $this->dataFolder;
    }
    
    public function setDataFolder($dataFolder)
    {
        $this->dataFolder = $this->validDataFolder($dataFolder);
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


    /* FILE DATA REQUEST METHODS
     *************************************************************************/
    public function setFileDataRequest($format)
    {
        $this->fileDataRequest = $this->getFileDataRequestFromName($format);
        return $this;
    }

    public function getFileDataRequest()
    {
        if (!$this->fileDataRequest) {
            return (new FileDataRequestFactory)->get('yml');
        }
        return $this->fileDataRequest;
    }
    
    protected function getFileDataRequestFromName($format)
    {
        if (is_string($format)) {
            $format = (new FileDataRequestFactory)->get($format);
        }
        return $format;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        $data = $this->getFileDataRequest()->getContents($this->dataFolder, $id);
        if ($data) {
            foreach (array_keys($this->subAttributeList) as $subKey) {
                $data[$subKey] = $this->fetchSubAttributeById($id, $subKey);
            }
            return $data;
        }
        return null;
    }


    /* SAVE METHODS
     *************************************************************************/
    public function saveById($id, $data)
    {
        $exists = !is_null($id);
        if (!$exists) {
            $id = $this->findAvailableIdByData($data);
        }
        $this->getFileDataRequest()->putContents($this->dataFolder, $id, $data);
        return $id;
    }

    public function deleteById($id)
    {
        $status = $this->getFileDataRequest()->unlink($this->dataFolder, $id);
        return $status;
    }


    /* PROTECTED SOURCE FILE METHODS
     *************************************************************************/
    protected function findAvailableIdByData($data) {
        $name = '';
        if (isset($data[$this->idField]) && is_string($data[$this->idField])) {
            $name = $data[$this->idField];
        }
        $suffix = 0;
        do {
            $id = $this->getIdByNameAndSuffix($name, $suffix);
            $suffix++;
        } while ($this->getFileDataRequest()->exists($this->dataFolder, $id));
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
        $fileList = $this->getFileDataRequest()->getFolderList($this->dataFolder);
        foreach ($fileList as $file) {
            $file = basename($file);
            $id = substr($file, 0, strrpos($file, '.'));
            $idList[] = $id;
        }
        return $idList;
    }
}
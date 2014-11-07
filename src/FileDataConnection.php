<?php

namespace Elephant418\Model418;

use Elephant418\Model418\Core\DataConnection\FileDataRequest\FileDataRequestFactory;
use Elephant418\Model418\Core\DataConnection\FileDataRequest\JSONFileDataRequest;
use Elephant418\Model418\Core\DataConnection\FileDataRequest\YamlFileDataRequest;
use Elephant418\Model418\Core\DataConnection\FileDataRequest\MarkdownFileDataRequest;
use Elephant418\Model418\Core\DataConnection\IDataConnection;

JSONFileDataRequest::register();
YamlFileDataRequest::register();
MarkdownFileDataRequest::register();

class FileDataConnection implements IDataConnection
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $fileDataRequest;
    protected $dataFolder;
    protected $idField = 'name';
    protected $subAttributeList = array();


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


    /* DATA FOLDER METHODS
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


    /* SUB ATTRIBUTES METHODS
     *************************************************************************/
    public function setSubAttribute($key, $id = null, $format = null)
    {
        $this->unsetSubAttribute($key);
        if (is_null($id)) {
            $id = $key;
        }
        if (in_array($id, $this->subAttributeList)) {
            throw new \RuntimeException('Another sub attribute use the id: '.$id);
        }
        $subAttribute = array();
        $subAttribute['id'] = $id;
        if (!is_null($format)) {
            $fileDataRequest = $this->getFileDataRequestFromName($format);
            $subAttribute['fileDataRequest'] = $fileDataRequest;
        }
        $this->subAttributeList[$key] = $subAttribute;
        return $this;
    }
    
    public function unsetSubAttribute($key)
    {
        unset($this->subAttributeList[$key]);
        return $this;
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
            $id = $this->findAvailableIdByData($data);
        }
        foreach (array_keys($this->subAttributeList) as $subKey) {
            $subData = null;
            if (isset($data[$subKey])) {
                $subData = $data[$subKey];
                unset($data[$subKey]);
            }
            $this->saveSubAttributeById($id, $subKey, $subData);
        }
        $this->getFileDataRequest()->putContents($this->dataFolder, $id, $data);
        return $id;
    }

    public function deleteById($id)
    {
        foreach (array_keys($this->subAttributeList) as $subKey) {
            $this->deleteSubAttributeById($id, $subKey);
        }
        $status = $this->getFileDataRequest()->unlink($this->dataFolder, $id);
        return $status;
    }


    /* PROTECTED SUB ATTRIBUTE METHODS
     *************************************************************************/
    protected function saveSubAttributeById($id, $subKey, $subData)
    {
        $subId = $this->getSubAttributeId($id, $subKey);
        $this->getSubAttributeFileDataRequest($subKey)
            ->putContents($this->dataFolder, $subId, $subData);
    }

    protected function deleteSubAttributeById($id, $subKey)
    {
        $subId = $this->getSubAttributeId($id, $subKey);
        $this->getSubAttributeFileDataRequest($subKey)
            ->unlink($this->dataFolder, $subId);
    }

    protected function fetchSubAttributeById($id, $subKey)
    {
        $subId = $this->getSubAttributeId($id, $subKey);
        $subData = $this->getSubAttributeFileDataRequest($subKey)
            ->getContents($this->dataFolder, $subId);
        return $subData;
    }

    protected function getSubAttributeId($id, $subKey)
    {
        $subId = $id.'.'.$this->subAttributeList[$subKey]['id'];
        return $subId;
    }
    
    protected function getSubAttributeFileDataRequest($subKey) {
        if (isset($this->subAttributeList[$subKey]['fileDataRequest'])) {
            return $this->subAttributeList[$subKey]['fileDataRequest'];
        }
        return $this->getFileDataRequest();
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
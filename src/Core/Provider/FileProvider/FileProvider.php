<?php

namespace Model418\Core\Provider\FileProvider;

use Model418\Core\Provider\IProvider;
use Model418\Core\Provider\CacheDumpProvider;
use Model418\Core\Request\FileRequestFactory;
use Model418\Core\Request\TextFileRequest;
use Model418\Core\Request\JSONFileRequest;
use Model418\Core\Request\YamlFileRequest;
use Model418\Core\Request\MarkdownFileRequest;

TextFileRequest::register();
JSONFileRequest::register();
YamlFileRequest::register();
MarkdownFileRequest::register();

class FileProvider extends CacheDumpProvider implements IProvider
{


    /* ATTRIBUTES
     *************************************************************************/
    protected $fileRequest;
    protected $folder;


    /* FOLDER METHODS
     *************************************************************************/
    public function getFolder()
    {
        return $this->folder;
    }
    
    public function setFolder($folder)
    {
        $this->folder = $this->validFolder($folder);
        return $this;
    }

    protected function validFolder($folder)
    {
        $realFolder = realpath($folder);
        if (!$realFolder) {
            throw new \RuntimeException('This data folder does not exist: ' . $folder);
        }
        return $realFolder;
    }


    /* FILE REQUEST METHODS
     *************************************************************************/
    public function setFileRequest($format)
    {
        $this->fileRequest = $this->getFileRequestFromName($format);
        return $this;
    }

    public function getFileRequest()
    {
        if (!$this->fileRequest) {
            return (new FileRequestFactory)->get('yml');
        }
        return $this->fileRequest;
    }
    
    protected function getFileRequestFromName($format)
    {
        if (is_string($format)) {
            $format = (new FileRequestFactory)->get($format);
        }
        return $format;
    }


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        $data = $this->getFileRequest()->getContents($this->folder, $id);
        return $data;
    }


    /* SAVE METHODS
     *************************************************************************/
    public function saveById($id, $data)
    {
        $exists = !is_null($id);
        if (!$exists) {
            $id = $this->findAvailableIdByData($data);
        }
        $this->getFileRequest()->putContents($this->folder, $id, $data);
        return $id;
    }

    public function deleteById($id)
    {
        $status = $this->getFileRequest()->unlink($this->folder, $id);
        return $status;
    }


    /* PROTECTED SOURCE FILE METHODS
     *************************************************************************/
    protected function isIdAvailable($id)
    {
        return !$this->getFileRequest()->exists($this->folder, $id);
    }

    protected function getAllIds()
    {
        $idList = array();
        $fileList = $this->getFileRequest()->getFolderList($this->folder);
        foreach ($fileList as $file) {
            $file = basename($file);
            $id = substr($file, 0, strrpos($file, '.'));
            $idList[] = $id;
        }
        return $idList;
    }
}
<?php

namespace Elephant418\Model418\DataConnection;

abstract class FileDataRequest implements IFileDataRequest
{
    
    /* ATTRIBUTES
     *************************************************************************/
    public static $extension = '';
    

    /* PUBLIC METHODS
     *************************************************************************/
    public function getContents($folder, $id)
    {
        if (!$this->exists($folder, $id)) {
            return null;
        }
        $filePath = $this->getFilePath($folder, $id);
        $text = file_get_contents($filePath);
        $data = $this->getDataFromText($id, $text);
        return $data;
    }

    public function putContents($folder, $id, $data)
    {
        $filePath = $this->getFilePath($folder, $id);
        $text = $this->getTextFromData($id, $data);
        return file_put_contents($filePath, $text);
    }
    
    public function exists($folder, $id)
    {
        $filePath = $this->getFilePath($folder, $id);
        return file_exists($filePath);
    }

    public function unlink($folder, $id)
    {
        $filePath = $this->getFilePath($folder, $id);
        return unlink($filePath);
    }

    public function getFolderList($folder)
    {
        $filePattern = $this->getFilePath($folder);
        return glob($filePattern);
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function getFilePath($folder, $id='*') {
        $filePath = $folder.'/'.$id;
        if (!static::$extension) {
            return $filePath;
        }
        return $filePath.'.'.static::$extension;
    }
    
    protected function getDataFromText($id, $text)
    {
        return $text;
    }

    protected function getTextFromData($id, $data)
    {
        return $data;
    }
}
<?php

namespace Elephant418\Model418\DataConnector;

class FileRequest
{

    public function getContents($fileName)
    {
        if (!$this->exists($fileName)) {
            return null;
        }
        return file_get_contents($fileName);
    }

    public function exists($fileName)
    {
        return file_exists($fileName);
    }

    public function putContents($fileName, $data)
    {
        return file_put_contents($fileName, $data);
    }

    public function getList($filePattern)
    {
        return glob($filePattern);
    }

    public function unlink($fileName)
    {
        return unlink($fileName);
    }
}
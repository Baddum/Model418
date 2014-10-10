<?php

namespace Elephant418\Packy\DataConnector;

class FileRequest
{
    
    public function getContents($fileName)
    {
        if (!file_exists($fileName)) {
            return null;
        }
        return file_get_contents($fileName);
    }

    public function putContents($fileName, $data)
    {
        return file_put_contents($fileName, $data);
    }

    public function getList($filePattern)
    {
        return glob($filePattern);
    }
}
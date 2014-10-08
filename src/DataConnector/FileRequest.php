<?php

namespace Elephant418\Packy\DataConnector;

class FileRequest
{
    
    public function getOne($file)
    {
        if (!file_exists($file)) {
            return null;
        }
        return file_get_contents($file);
    }

    protected function getAll($pattern)
    {
        return glob($pattern);
    }
}
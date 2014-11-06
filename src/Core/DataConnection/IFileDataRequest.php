<?php

namespace Elephant418\Model418\Core\DataConnection;

interface IFileDataRequest
{
    
    public function getContents($folder, $id);

    public function putContents($folder, $id, $data);
    
    public function exists($folder, $id);

    public function unlink($folder, $id);

    public function getFolderList($folder);
}
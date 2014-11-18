<?php

namespace Elephant418\Model418\Core\Request;

interface INoRelationRequest
{
    
    public function getContents($folder, $id);

    public function putContents($folder, $id, $data);
    
    public function exists($folder, $id);

    public function unlink($folder, $id);

    public function getIdList($folder);
}
<?php

namespace Baddum\Model418\Core\Request;

interface IKeyValueRequest
{
    public function getContents($folderList, $id);

    public function putContents($folder, $id, $data);

    public function exists($folder, $id);

    public function unlink($folder, $id);

    public function getIdList($folder);
}
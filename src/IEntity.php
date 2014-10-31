<?php

namespace Elephant418\Model418;

interface IEntity
{

    public function byId($id);

    public function byIdList($idList);

    public function all($limit = null, $offset = null, &$count = false);

    public function saveById($id, $data);

    public function deleteById($id);
}
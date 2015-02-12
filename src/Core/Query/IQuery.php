<?php

namespace Baddum\Model418\Core\Query;

interface IQuery
{

    public function fetchById($id);

    public function fetchByIdList($idList);

    public function fetchAll($limit = null, $order = null, $offset = null, &$count = false);

    public function saveById($id, $data);

    public function deleteById($id);
}
<?php

namespace Model418\Core\Provider;

abstract class NoRelationProvider
{


    /* FETCHING METHODS
     *************************************************************************/
    public function fetchById($id)
    {
        throw new \LogicException('This method must be overridden');
    }

    public function fetchByIdList($idList)
    {
        $dataList = array();
        foreach ($idList as $id) {
            $data = $this->fetchById($id);
            if ($data) {
                $dataList[$id] = $data;
            }
        }
        return $dataList;
    }

    public function fetchAll($limit = null, $offset = null, &$count = false)
    {
        $idList = $this->getAllIds();
        if ($count !== false) {
            $count = count($idList);
        }
        $idList = $this->slice($idList, $limit, $offset);
        $dataList = $this->fetchByIdList($idList);
        return $dataList;
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function getAllIds()
    {
        throw new \LogicException('This method must be overridden');
    }

    public function slice($dataList, $limit = null, $offset = null)
    {
        if (is_null($offset)) {
            $offset = 0;
        }
        if (!is_null($limit)) {
            $dataList = array_slice($dataList, $offset, $limit);
        }
        return $dataList;
    }
}
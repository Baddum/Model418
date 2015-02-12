<?php

namespace Baddum\Model418;

use Baddum\Model418\Core\ListObject;

class ModelList extends ListObject
{


    /* INITIALIZATION
     *************************************************************************/
    public function init($modelArray)
    {
        $this->exchangeArray($modelArray);
        return $this;
    }


    /* FILTERING METHODS
     *************************************************************************/
    public function filter($name, $value)
    {
        $this->filterCallback(function ($model) use ($name, $value) {
            return ($model->get($name) === $value);
        });
        return $this;
    }

    public function order($name, $reverse = false)
    {
        $this->orderCallback(function ($a, $b) use ($name, $reverse) {
            $intReverse = -2*$reverse +1;
            return strcmp($a->get($name), $b->get($name)) * $intReverse;
        });
        return $this;
    }


    /* GETTER & SETTER
     *************************************************************************/
    public function get($name)
    {
        $valueList = array();
        foreach ($this as $model) {
            $valueList[$model->id] = $model->get($name);
        }
        return $valueList;
    }

    public function set($name, $value)
    {
        foreach ($this as $model) {
            $model->set($name, $value);
        }
        return $this;
    }


    /* PUBLIC STORING METHODS
     *************************************************************************/
    public function save()
    {
        foreach ($this as $model) {
            $model->save();
        }
        return $this;
    }

    public function delete()
    {
        foreach ($this as $model) {
            $model->delete();
        }
        return $this;
    }
}
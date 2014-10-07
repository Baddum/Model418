<?php

namespace Test\Elephant418\Packy;

require_once(__DIR__ . '/../vendor/autoload.php');

use Test\Elephant418\Packy\Resources\SimpleCase\TestModel as SimpleModel;
use Test\Elephant418\Packy\Resources\SeparateCase\TestModel as SeparateModel;

class ArrayObjectTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSimple()
    {
        $model = (new SimpleModel)->fetchById('test');
        $this->assertEquals('myValue', $model['myName'], 'Get model attribute value with array accessor');
        $this->assertEquals('myValue', $model->myName, 'Get model attribute value with object accessor');
        $this->assertEquals('myValue', $model->get('myName'), 'Get model attribute value with method accessor');
    }

    public function testCustomFetch()
    {
        $model = (new SimpleModel)->fetchTest();
        $this->assertEquals('myValue', $model->myName);
    }

    public function testSeparateCustomFetch()
    {
        $model = (new SeparateModel)->fetchTest();
        $this->assertEquals('myValue', $model->myName);
    }
}
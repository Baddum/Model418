<?php

namespace Test\Elephant418\Model418\Test;

use Test\Elephant418\Model418\Resources\SimpleCase\ResourceModel as SimpleModel;
use Test\Elephant418\Model418\Resources\SeparateCase\ResourceModel as SeparateModel;
use Test\Elephant418\Model418\Resources\NoDataConnectorCase\ResourceModel as NoDataConnectorModel;

class EndToEndTest extends \PHPUnit_Framework_TestCase
{

    public function testSimpleAccessor()
    {
        $model = (new SimpleModel)->fetchById('test');
        $this->assertEquals('myValue', $model['myName'], 'Get model attribute value with array accessor');
        $this->assertEquals('myValue', $model->myName, 'Get model attribute value with object accessor');
        $this->assertEquals('myValue', $model->get('myName'), 'Get model attribute value with method accessor');
    }

    public function testSimpleCustomFetch()
    {
        $model = (new SimpleModel)->fetchTest();
        $this->assertEquals('myValue', $model->myName);
    }

    public function testSeparate()
    {
        $model = (new SeparateModel)->fetchTest();
        $this->assertEquals('myValue', $model->myName);
    }

    public function testNoDataConnector()
    {
        $this->setExpectedException('LogicException');
        (new NoDataConnectorModel)->fetchById('test');
    }

    public function testSaveAndDelete()
    {
        $model = new SimpleModel;
        $model->myName = 'truc';
        $this->assertFalse($model->exists(), 'The model does not exist');
        $this->assertEquals('truc', $model->myName);
        $model->save();
        $this->assertTrue($model->exists(), 'The model exists');
        $id = $model->id;
        unset($model);
        $model = (new SimpleModel)->fetchById($id);
        $this->assertTrue($model->exists(), 'The model exists');
        $this->assertEquals('truc', $model->myName);
        $model->delete();
        unset($model);
        $model = (new SimpleModel)->fetchById($id);
        $this->assertFalse($model->exists(), 'The model exists');
    }
}
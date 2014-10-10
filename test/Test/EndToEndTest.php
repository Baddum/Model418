<?php

namespace Test\Elephant418\Packy\Test;

use Test\Elephant418\Packy\Resources\SimpleCase\ResourceModel as SimpleModel;
use Test\Elephant418\Packy\Resources\SeparateCase\ResourceModel as SeparateModel;
use Test\Elephant418\Packy\Resources\NoDataConnectorCase\ResourceModel as NoDataConnectorModel;

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
}
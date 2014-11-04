<?php

namespace Test\Elephant418\Model418\Test;

use Test\Elephant418\Model418\Resources\SimpleCase\ResourceModel as SimpleModel;
use Test\Elephant418\Model418\Resources\SeparateCase\ResourceModel as SeparateModel;
use Test\Elephant418\Model418\Resources\NoDataConnectionCase\ResourceModel as NoDataConnectionModel;
use Test\Elephant418\Model418\Resources\MultipleDataSourceCase\ResourceModel as MultipleDataSourceModel;

class EndToEndTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSimpleAccessor()
    {
        $model = (new SimpleModel)->fetch()->byId('test');
        $this->assertEquals('myValue', $model['myName'], 'Get model attribute value with array accessor');
        $this->assertEquals('myValue', $model->myName, 'Get model attribute value with object accessor');
        $this->assertEquals('myValue', $model->get('myName'), 'Get model attribute value with method accessor');
    }

    public function testSimpleCustomFetch()
    {
        $model = (new SimpleModel)->fetch()->test();
        $this->assertEquals('myValue', $model->myName);
    }
    
    public function testSeparate()
    {
        $model = (new SeparateModel)->fetch()->test();
        $this->assertEquals('myValue', $model->myName);
    }
    
    public function testNoDataConnection()
    {
        $this->setExpectedException('LogicException');
        (new NoDataConnectionModel)->fetch()->byId('test');
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
        $model = (new SimpleModel)->fetch()->byId($id);
        $this->assertTrue($model->exists(), 'The model exists');
        $this->assertEquals('truc', $model->myName);
        $model->delete();
        unset($model);
        $model = (new SimpleModel)->fetch()->byId($id);
        $this->assertFalse($model->exists(), 'The model exists');
    }
    
    public function testMultipleDataSource()
    {
        $model = (new SimpleModel)->fetch()->byId('test');
        $this->assertTrue($model->exists(), 'The model `test` fetched with a simple source exists');
        $model = (new MultipleDataSourceModel)->fetch()->byId('test');
        $this->assertTrue($model->exists(), 'The model `test` fetched with multiple sources exists');
        
        $model = (new SimpleModel)->fetch()->byId('test2');
        $this->assertFalse($model->exists(), 'The model `test2` fetched with a simple source does not exist');
        $model = (new MultipleDataSourceModel)->fetch()->byId('test2');
        $this->assertTrue($model->exists(), 'The model `test2` fetched with multiple sources exists');
        
        $model = (new SimpleModel)->fetch()->byId('test3');
        $this->assertFalse($model->exists(), 'The model `test3` fetched with a simple source does not exist');
        $model = (new MultipleDataSourceModel)->fetch()->byId('test3');
        $this->assertFalse($model->exists(), 'The model `test3` fetched with multiple sources does not exist');
        
        $model = (new MultipleDataSourceModel)
            ->set('myName', 'test4')
            ->save();
        $filePath = $model->getWritableDataFolder().'/'.$model->id.'.json';
        $this->assertTrue(file_exists($filePath), 'The data source file for `test4` is in the right folder');
        $model->delete();
        $this->assertFalse(file_exists($filePath), 'The data source file for `test4` was deleted from the right folder');
    }
}
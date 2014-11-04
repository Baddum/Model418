<?php

namespace Test\Elephant418\Model418\Test;

use Test\Elephant418\Model418\Resources\SimpleCase\ResourceModel as SimpleModel;
use Test\Elephant418\Model418\Resources\SeparateCase\ResourceModel as SeparateModel;
use Test\Elephant418\Model418\Resources\NoDataConnectionCase\ResourceModel as NoDataConnectionModel;
use Test\Elephant418\Model418\Resources\MultipleDataSourceCase\ResourceModel as MultipleDataSourceModel;
use Test\Elephant418\Model418\Resources\YamlCase\ResourceModel as YamlModel;

class EndToEndTest extends \PHPUnit_Framework_TestCase
{
    
    public function testSimpleAccessor()
    {
        $model = (new SimpleModel)->query()->fetchById('test');
        $this->assertEquals('myValue', $model['myName'], 'Get model attribute value with array accessor');
        $this->assertEquals('myValue', $model->myName, 'Get model attribute value with object accessor');
        $this->assertEquals('myValue', $model->get('myName'), 'Get model attribute value with method accessor');
    }

    public function testSimpleCustomFetch()
    {
        $model = (new SimpleModel)->query()->fetchTest();
        $this->assertEquals('myValue', $model->myName);
    }
    
    public function testSeparate()
    {
        $model = (new SeparateModel)->query()->fetchTest();
        $this->assertEquals('myValue', $model->myName);
    }
    
    public function testNoDataConnection()
    {
        $this->setExpectedException('LogicException');
        (new NoDataConnectionModel)->query()->fetchById('test');
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
        $model = (new SimpleModel)->query()->fetchById($id);
        $this->assertTrue($model->exists(), 'The model exists');
        $this->assertEquals('truc', $model->myName);
        $model->delete();
        unset($model);
        $model = (new SimpleModel)->query()->fetchById($id);
        $this->assertFalse($model->exists(), 'The model exists');
    }
    
    public function testMultipleDataSource()
    {
        $simpleEntity = (new SimpleModel)->query();
        $multipleDataSourceEntity = (new MultipleDataSourceModel)->query();
        $model = $simpleEntity->fetchById('test');
        $this->assertTrue($model->exists(), 'The model `test` fetched with a simple source exists');
        $model = $multipleDataSourceEntity->fetchById('test');
        $this->assertTrue($model->exists(), 'The model `test` fetched with multiple sources exists');
        
        $model = $simpleEntity->fetchById('test2');
        $this->assertFalse($model->exists(), 'The model `test2` fetched with a simple source does not exist');
        $model = $multipleDataSourceEntity->fetchById('test2');
        $this->assertTrue($model->exists(), 'The model `test2` fetched with multiple sources exists');
        
        $model = $simpleEntity->fetchById('test3');
        $this->assertFalse($model->exists(), 'The model `test3` fetched with a simple source does not exist');
        $model = $multipleDataSourceEntity->fetchById('test3');
        $this->assertFalse($model->exists(), 'The model `test3` fetched with multiple sources does not exist');
        
        $model = (new MultipleDataSourceModel)
            ->set('myName', 'test4')
            ->save();
        $filePath = $model->getWritableDataFolder().'/'.$model->id.'.json';
        $this->assertTrue(file_exists($filePath), 'The data source file for `test4` is in the right folder');
        $model->delete();
        $this->assertFalse(file_exists($filePath), 'The data source file for `test4` was deleted from the right folder');
    }

    public function testYamlDataSource()
    {
        $model = (new YamlModel)->query()->fetchYaml();
        $this->assertTrue($model->exists(), 'The model exists');
        $this->assertEquals('myValue', $model->myName);
    }
}
<?php

namespace Test\Elephant418\Packy\Test;

use Elephant418\Packy\DataConnector\FileDataConnector;

class FileDataConnectorTest extends \PHPUnit_Framework_TestCase
{


    /* DATA FOLDER TEST METHODS
     *************************************************************************/
    public function testDataFolderWithoutEndingSlash()
    {
        $expectedDataFolder = __DIR__;
        $actualDataFolder = (new FileDataConnector)
            ->setDataFolder($expectedDataFolder)
            ->getDataFolder();
        $this->assertEquals($expectedDataFolder, $actualDataFolder);
    }

    public function testDataFolderWithEndingSlash()
    {
        $expectedDataFolder = __DIR__;
        $actualDataFolder = (new FileDataConnector)
            ->setDataFolder($expectedDataFolder.'/')
            ->getDataFolder();
        $this->assertEquals($expectedDataFolder, $actualDataFolder);
    }

    public function testUnexistingDataFolder()
    {
        $unexistingDataFolder = '/my/unexisting/data/folder';
        $this->setExpectedException('RuntimeException');
        (new FileDataConnector)
            ->setDataFolder($unexistingDataFolder);
    }
    

    /* FETCHING TEST METHODS
     *************************************************************************/
    /**
     * @dataProvider providerJsonData
     */
    public function testFetchById($data)
    {
        $dataFolder = __DIR__;
        $id = 'mySuperTest';
        $expectedData = $data;
        $expectedData['id'] = $id;

        $stub = $this->getFileRequestStub();
        $stub->expects($this->once())
            ->method('getContents')
            ->with($dataFolder.'/'.$id.'.json')
            ->will($this->returnValue(json_encode($data, true)));

        $dataConnector = $this->getFileDataConnector($stub, $dataFolder);
        $actualData = $dataConnector->fetchById($id);
        $this->assertEquals($expectedData, $actualData);
    }
    
    public function providerJsonData()
    {
        $data = array();
        $data['empty'] = array();
        $data['dataType'] = array('string'=>'some value', 'number'=>42, 'array'=>array('first'=>2, 'second'=>2), 'list'=>array(2, '3'));
        foreach (array_keys($data) as $key) {
            $data[$key] = array($data[$key]);
        }
        return $data;
    }
    
    /**
     * @dataProvider providerIdList
     */
    public function testFetchByIdList($idList)
    {
        $stub = $this->getFileRequestStub();
        $stub->expects($this->exactly(count($idList)))
            ->method('getContents')
            ->will($this->returnValue('{}', true));

        $dataConnector = $this->getFileDataConnector($stub);
        $actualDataList = $dataConnector->fetchByIdList($idList);
        $this->assertEquals($idList, array_keys($actualDataList));
    }

    /**
     * @dataProvider providerIdList
     */
    public function testFetchAll($idList)
    {
        $stub = $this->getFileRequestStub();
        $stub->expects($this->exactly(count($idList)))
            ->method('getContents')
            ->will($this->returnValue('{}', true));
        $stub->expects($this->once())
            ->method('getList')
            ->will($this->returnValue(array_map(function($a){
                return __DIR__.'/'.$a.'.json';
            }, $idList), true));

        $dataConnector = $this->getFileDataConnector($stub);
        $actualDataList = $dataConnector->fetchAll();
        $this->assertEquals($idList, array_keys($actualDataList));
    }

    public function providerIdList()
    {
        $data = array();
        $data['none'] = array();
        $data['one'] = array('1');
        $data['two'] = array('coco', 'ananas');
        $data['three'] = array('some', 'id', 'list');
        foreach (array_keys($data) as $key) {
            $data[$key] = array($data[$key]);
        }
        return $data;
    }


    /* PROTECTED METHODS
     *************************************************************************/
    protected function getFileDataConnector($stub, $dataFolder = __DIR__) {
        return (new FileDataConnector($stub))
            ->setDataFolder($dataFolder);
    }

    protected function getFileRequestStub() {
        return $this->getMock('Elephant418\\Packy\\DataConnector\\FileRequest');
    }
}
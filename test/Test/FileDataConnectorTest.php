<?php

namespace Test\Elephant418\Packy\Test;

use Elephant418\Packy\DataConnector\FileDataConnector;

class FileDataConnectorTest extends \PHPUnit_Framework_TestCase
{


    /* DATA FOLDER
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
    

    /* FETCHING
     *************************************************************************/
    /**
     * @dataProvider providerJsonData
     */
    public function testFetchingJsonData($data)
    {
        $dataFolder = __DIR__;
        $id = 'mySuperTest';
        $expectedData = $data;
        $expectedData['id'] = $id;
        
        $stub = $this->getMock('Elephant418\\Packy\\DataConnector\\FileRequest');
        $stub->expects($this->once())
            ->method('getContents')
            ->with($dataFolder.'/'.$id.'.json')
            ->will($this->returnValue(json_encode($data, true)));

        $dataConnector = (new FileDataConnector($stub))
            ->setDataFolder($dataFolder);
        $actualData = $dataConnector->fetchById($id);
        $this->assertEquals($expectedData, $actualData);
    }
    
    public function providerJsonData()
    {
        $data = array();
        $data['empty'] = array(array());
        $data['dataType'] = array(array('string'=>'some value', 'number'=>42, 'array'=>array('first'=>2, 'second'=>2), 'list'=>array(2, '3')));
        return $data;
    }
}
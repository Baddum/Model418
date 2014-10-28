<?php

namespace Test\Elephant418\Model418\Test;

use Elephant418\Model418\DataConnector\FileDataConnector;

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
            ->setDataFolder($expectedDataFolder . '/')
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


    /* FETCH BY ID TEST METHODS
     *************************************************************************/
    /**
     * @dataProvider providerFetchByIdData
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
            ->with($dataFolder . '/' . $id . '.json')
            ->will($this->returnValue(json_encode($data, true)));
        $dataConnector = $this->getFileDataConnector($stub, $dataFolder);
        
        $actualData = $dataConnector->fetchById($id);
        $this->assertEquals($expectedData, $actualData);
    }

    public function providerFetchByIdData()
    {
        $data = array();
        $data['empty'] = array();
        $data['list'] = array(3, 1, 14, 1, 59);
        $data['associative'] = array('string' => 'some value', 'number' => 42);
        $data['nested'] = array('associative' => $data['associative'], 'list' => $data['list']);
        return $this->provideOnlyOneArgument($data);
    }


    /* FETCH BY ID LIST TEST METHODS
     *************************************************************************/
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


    /* FETCH ALL TEST METHODS
     *************************************************************************/
    /**
     * @dataProvider providerIdList
     */
    public function testFetchAll($idList)
    {
        $stub = $this->getFetchAllStub($idList, count($idList));
        $dataConnector = $this->getFileDataConnector($stub);
        $actualDataList = $dataConnector->fetchAll();
        $this->assertEquals($idList, array_keys($actualDataList));
    }

    /**
     * @dataProvider providerIdList
     */
    public function testFetchAllCount($idList)
    {
        $stub = $this->getFetchAllStub($idList, count($idList));
        $dataConnector = $this->getFileDataConnector($stub);
        $actualCount = 0;
        $actualDataList = $dataConnector->fetchAll(null, null, $actualCount);
        $this->assertEquals($idList, array_keys($actualDataList));
        $this->assertEquals(count($idList), $actualCount);
    }

    /**
     * @dataProvider providerIdListLimit
     */
    public function testFetchAllWithLimit($idList, $limit, $expectedIdList)
    {
        $stub = $this->getFetchAllStub($idList, count($expectedIdList));
        $dataConnector = $this->getFileDataConnector($stub);
        $actualDataList = $dataConnector->fetchAll($limit);
        $this->assertEquals(count($expectedIdList), count($actualDataList));
        $this->assertEquals($expectedIdList, array_keys($actualDataList));
    }

    /**
     * @dataProvider providerIdListLimit
     */
    public function testFetchAllWithLimitCount($idList, $limit, $expectedIdList)
    {
        $stub = $this->getFetchAllStub($idList, count($expectedIdList));
        $dataConnector = $this->getFileDataConnector($stub);
        $actualCount = 0;
        $actualDataList = $dataConnector->fetchAll($limit, null, $actualCount);
        $this->assertEquals(count($expectedIdList), count($actualDataList));
        $this->assertEquals(count($idList), $actualCount);
        $this->assertEquals($expectedIdList, array_keys($actualDataList));
    }

    /**
     * @dataProvider providerIdListLimitOffset
     */
    public function testFetchAllWithLimitOffset($idList, $limit, $offset, $expectedIdList)
    {
        $stub = $this->getFetchAllStub($idList, count($expectedIdList));
        $dataConnector = $this->getFileDataConnector($stub);
        $actualDataList = $dataConnector->fetchAll($limit, $offset);
        $this->assertEquals(count($expectedIdList), count($actualDataList));
        $this->assertEquals($expectedIdList, array_keys($actualDataList));
    }

    /**
     * @dataProvider providerIdListLimitOffset
     */
    public function testFetchAllWithLimitOffsetCount($idList, $limit, $offset, $expectedIdList)
    {
        $stub = $this->getFetchAllStub($idList, count($expectedIdList));
        $dataConnector = $this->getFileDataConnector($stub);
        $actualCount = 0;
        $actualDataList = $dataConnector->fetchAll($limit, $offset, $actualCount);
        $this->assertEquals(count($expectedIdList), count($actualDataList));
        $this->assertEquals(count($idList), $actualCount);
        $this->assertEquals($expectedIdList, array_keys($actualDataList));
    }

    public function providerIdList()
    {
        $data = array();
        $data['empty'] = array();
        $data['one'] = array('1');
        $data['two'] = array('coco', 'ananas');
        $data['three'] = array('some', 'id', 'list');
        return $this->provideOnlyOneArgument($data);
    }

    public function providerIdListLimit()
    {
        $list = array('some', 'id', 'list');
        $data = array();
        $data['empty-1'] = array(array(), 1, array());
        $data['empty-5'] = array(array(), 5, array());
        $data['alone-1'] = array(array('1'), 1, array('1'));
        $data['alone-5'] = array(array('1'), 5, array('1'));
        $data['list-1'] = array($list, 1, array('some'));
        $data['list-5'] = array($list, 5, $list);
        return $data;
    }

    public function providerIdListLimitOffset()
    {
        $list = array('some', 'id', 'list');
        $data = array();
        $data['empty-1-0'] = array(array(), 1, 0, array());
        $data['empty-5-1'] = array(array(), 5, 1, array());
        $data['empty-5-3'] = array(array(), 5, 3, array());
        $data['alone-1-0'] = array(array('1'), 1, 0, array('1'));
        $data['alone-1-1'] = array(array('1'), 1, 1, array());
        $data['alone-5-0'] = array(array('1'), 5, 0, array('1'));
        $data['alone-5-1'] = array(array('1'), 5, 1, array());
        $data['list-1-0'] = array($list, 1, 0, array('some'));
        $data['list-1-1'] = array($list, 1, 1, array('id'));
        $data['list-1-3'] = array($list, 1, 3, array());
        $data['list-5-0'] = array($list, 5, 0, $list);
        $data['list-5-1'] = array($list, 5, 1, array('id', 'list'));
        $data['list-5-3'] = array($list, 5, 3, array());
        return $data;
    }

    
    /* SAVE TEST METHODS
     *************************************************************************/
    /**
     * @dataProvider providerSaveData
     */
    public function testSaveNewEntry($data, $expectedId)
    {
        $dataFolder = __DIR__;

        $stub = $this->getFileRequestStub();
        $stub
            ->expects($this->once())
            ->method('exists')
            ->with(__DIR__.'/'.$expectedId.'.json')
            ->will($this->returnValue(false));
        $stub
            ->expects($this->once())
            ->method('putContents')
            ->with(__DIR__.'/'.$expectedId.'.json', json_encode($data, true));
        
        $dataConnector = $this->getFileDataConnector($stub, $dataFolder);
        $actualId = $dataConnector->save(null, $data);
        $this->assertEquals($expectedId, $actualId);
    }

    /**
     * @dataProvider providerSaveData
     */
    public function testSaveUpdate($data, $id)
    {
        $dataFolder = __DIR__;

        $stub = $this->getFileRequestStub();
        $stub
            ->expects($this->once())
            ->method('putContents')
            ->with($dataFolder.'/'.$id.'.json', json_encode($data, true));

        $dataConnector = $this->getFileDataConnector($stub, $dataFolder);
        $dataConnector->save($id, $data);
    }

    public function providerSaveData()
    {
        $data = array();
        $data['empty'] = array(array(), '1');
        $data['no-name'] = array(array('age'=>'24', 'city'=>'Lyon'), '1');
        $data['named'] = array(array('name'=>'Albert', 'city'=>'Lyon'), 'Albert');
        return $data;
    }

    /**
     * @dataProvider providerSaveDataWithExistingFile
     */
    public function testSaveNewEntryWithExistingFile($data, $tryMax, $expectedId)
    {
        $dataFolder = __DIR__;
        $tryItem = 0;

        $stub = $this->getFileRequestStub();
        $stub
            ->expects($this->exactly($tryMax))
            ->method('exists')
            ->will($this->returnCallback(function() use (&$tryItem, $tryMax) {
                $tryItem++;
                return ($tryItem < $tryMax);
            }));
        $stub
            ->expects($this->once())
            ->method('putContents')
            ->with(__DIR__.'/'.$expectedId.'.json');

        $dataConnector = $this->getFileDataConnector($stub, $dataFolder);
        $actualId = $dataConnector->save(null, $data);
        $this->assertEquals($expectedId, $actualId);
    }

    public function providerSaveDataWithExistingFile()
    {
        $data = array();
        $data['empty-1'] = array(array(), 1, '1');
        $data['empty-4'] = array(array(), 4, '4');
        $data['no-name-1'] = array(array('age'=>'24', 'city'=>'Lyon'), 1, '1');
        $data['no-name-4'] = array(array('age'=>'24', 'city'=>'Lyon'), 4, '4');
        $data['named-1'] = array(array('name'=>'Albert', 'city'=>'Lyon'), 1, 'Albert');
        $data['named-2'] = array(array('name'=>'Albert', 'city'=>'Lyon'), 2, 'Albert-2');
        $data['named-4'] = array(array('name'=>'Albert', 'city'=>'Lyon'), 4, 'Albert-4');
        return $data;
    }

    
    /* PROTECTED METHODS
     *************************************************************************/
    protected function getFileDataConnector($stub, $dataFolder = __DIR__, $idField = 'name')
    {
        return (new FileDataConnector($stub))
            ->setDataFolder($dataFolder)
            ->setIdField($idField);
    }

    protected function getFileRequestStub()
    {
        return $this->getMock('Elephant418\\Model418\\DataConnector\\FileRequest');
    }

    protected function getFetchAllStub($idList, $occurrenceContents, $occurrenceList=1)
    {
        $stub = $this->getFileRequestStub();
        $stub->expects($this->exactly($occurrenceList * $occurrenceContents))
            ->method('getContents')
            ->will($this->returnValue('{}'));
        $stub->expects($this->exactly($occurrenceList))
            ->method('getList')
            ->will($this->returnValue(array_map(function ($a) {
                return __DIR__ . '/' . $a . '.json';
            }, $idList), true));
        return $stub;
    }

    protected function provideOnlyOneArgument($data)
    {
        foreach (array_keys($data) as $key) {
            $data[$key] = array($data[$key]);
        }
        return $data;
    }
}
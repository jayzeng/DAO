<?php
/**
 * @category testing
 * @package Test_Dao
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 */
namespace Test\Dao;

/**
 * Test Db adapter
 * @category testing
 * @package Test_Dao
 */
class AdapterTest extends \PHPUnit_Framework_TestCase
{
    private $_adapter = null;

    public function setUp() {
        $this->_adapter = new \Dao\Adapter();
    }

    public function tearDown() {
        unset($this->_adapter);
    }

    public function testSetAndGetAdapter() {
        // Test a support driver
        $supportedDrivers = new \Dao\DriverEnum();
        foreach($supportedDrivers->getValues() as $dbDriver) {
            $this->_adapter->setDriver($dbDriver);
            $this->assertSame($dbDriver, $this->_adapter->getDriver());

            $this->assertInstanceOf('\\Dao\\Adapter\\'.$dbDriver, $this->_adapter->getDriverAdapter());
            $this->assertInstanceOf('\\Dao\\Adapter\\'.$dbDriver.'\\Connection', $this->_adapter->getConnection());
        }

        // Test non-supported driver
        $noSuchDriver = 'noSuchDriver';
        $this->_adapter->setDriver($noSuchDriver);
        $this->assertSame($noSuchDriver, $this->_adapter->getDriver());

        $this->assertFalse($this->_adapter->getDriverAdapter());
    }

    public function testDbAdapter() {
        $db = new \Dao\Db();

        // No such adapter, throw an exception
        try {
            $db->setAdapter('foo');
            $db->getAdapter('foo');
        } catch(\InvalidArgumentException $e) {
            $this->assertSame( 'Class foo does not exist', $e->getMessage() );
        }

        $anotherDb = new \Dao\Db();
        $this->assertInstanceOf( '\Dao\Adapter', $anotherDb->getAdapter() );
    }
}
?>

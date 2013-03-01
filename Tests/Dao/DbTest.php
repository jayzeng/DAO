<?php
/**
 * @category testing
 * @package Test_Dao
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 */
namespace Test\Dao;

/**
 * @category testing
 * @package Test_Dao
 */
class DbTest extends \PHPUnit_Framework_TestCase
{
    private $_config = array();

    /**
     * @todo check if the following global variables are defined with values defined.
     * Skip test if necessary fields are not provided
     */
    public function setUp() {
        // Construct db connection paramaters, populating  values from the global configuration (Tests/TestConfiguration.php)
        $this->_config = array(
                'host'     => DB_HOST,
                'username' => DB_USERNAME,
                'password' => DB_PASSWORD,
                'dbname'   => DB_DBNAME,
                'port'     => DB_PORT,
                'socket'   => DB_SOCKET
                );

    }

    public function tearDown() {
        unset($this->_config);
    }

    /**
     * test invalidCredential()
     *
     * @return void
     */
    public function testInvalidCredential() {
        $this->assertNotEmpty(
                \Dao\DbFactory::initDatabase(
                    array('host' => '', 'username' => 'nosuchuserintheworld', 'password' => 'superawesomePassword1234')
                    )
                );

    }

    public function testDbFactory() {
        $validConnection = \Dao\DbFactory::initDatabase($this->_config);
        $this->assertInstanceOf('\Dao\Adapter\Mysqli\Connection', $validConnection);
        $this->assertTrue($validConnection->isConnected());

        // Server version should always return the same format, e.g: 5.1.4
        $this->assertRegExp('/\d\.\d\.\d/', $validConnection->getServerVersion());

        $this->assertInstanceOf('mysqli',$validConnection->getResource());

        // Test connection paramaters
        $this->assertSame($this->_config, $validConnection->getConnectionParams());

        $validConnection->disconnect();
        $this->assertFalse($validConnection->isConnected());

        $this->assertFalse($validConnection->disconnect());
    }

    public function testResultSet() {
        $connection = \Dao\DbFactory::initDatabase($this->_config);
        //$this->prepareUnitTestTable();
        $names = array(
                'jay'  => 'zeng',
                'lee'  => 'brown',
                'lucas'=> 'brown',
                'beau' => 'hoyt'
                );

        // Hold down all the newly insert idsksdjfhaskdjfajksdfas
        $ids = array();

        // Insert a batch of rows
        foreach($names as $firstName => $lastName) {
            $ids[] = $connection->insert(
                    sprintf(
                        'INSERT INTO dbUnitTest.Employee VALUES (null, %s, %s);',
                        $connection->quote($firstName),
                        $connection->quote($lastName)
                        )
                    );
        }

        $results = array();
        $dbResult = $connection->select('SELECT * FROM dbUnitTest.Employee');
        // $key is not being used, but we need to specify it in order to hit the
        // \Iterator::key()
        foreach($dbResult as $key => $result) {
            $results[] = $result;
        }

        // Do a very explicit equality check
        $this->assertEquals(
                array(
                        array(
                            'employee_id' => 1,
                            'first_name'  => 'jay',
                            'last_name'   => 'zeng'
                            ),
                        array(
                            'employee_id' => 2,
                            'first_name'  => 'lee',
                            'last_name'   => 'brown'
                            ),
                        array(
                            'employee_id' => 3,
                            'first_name'  => 'lucas',
                            'last_name'   => 'brown'
                            ),
                        array(
                            'employee_id' => 4,
                            'first_name'  => 'beau',
                            'last_name'   => 'hoyt'
                            )
                        ),
                $results
                );

        // Test the explicit getNumberOfRows()
        $this->assertEquals(4, $dbResult->getNumberOfRows());

        // Result implemented countable
        $this->assertEquals(4, count($dbResult));

        //Delete a row
        $connection->delete('DELETE FROM dbUnitTest.Employee WHERE employee_id = 4');

        // Now we should only have 3 rows
        $this->assertEquals(3, count($connection->select('SELECT * FROM dbUnitTest.Employee')));

        // Fetch the 1st row of the result set
        $this->assertEquals(
                    array(
                        'employee_id' => 1,
                        'first_name'  => 'jay',
                        'last_name'   => 'zeng'
                        ),
                    $connection->selectRow('SELECT * FROM dbUnitTest.Employee')
                    );

        // Now, move on to hit various exceptions
        try {
            $connection->select('SELECT NOSUCHFIELD FROM dbUnitTest.Employee;');
        } catch (\Dao\Exception\ReadException $e) {
            $errorMessage = $e->getDetails();
            $this->assertContains('Error message: Unknown column', $errorMessage);
            $this->assertContains('Error Code', $errorMessage);
            $this->assertContains('Sql Query', $errorMessage);
        }

        try {
            $connection->delete('DELETE FROM dbUnitTest.Employee WHERE lala = 100');
        } catch (\Dao\Exception\DeleteException $e) {
            $errorMessage = $e->getDetails();
            $this->assertContains('Error message: Unknown column', $errorMessage);
            $this->assertContains('Error Code', $errorMessage);
            $this->assertContains('Sql Query', $errorMessage);
        }

        try {
            $connection->insert('INSERT INTO dbUnitTest.Employee VALUES (null, null, null);');
        } catch (\Dao\Exception\WriteException $e) {
            $this->assertContains('Error message: Column \'first_name\' cannot be null', $e->getDetails());
        }

        $this->assertEquals(
                array(
                    'errorMessage' => 'Column \'first_name\' cannot be null',
                    'errorNumber'  => 1048
                    ),
                $connection->getError()
                );

        // update last row
        $connection->update(sprintf('UPDATE dbUnitTest.Employee SET first_name = "%s" WHERE employee_id = 1;', 'superMan'));
        $row = $connection->selectRow('SELECT first_name FROM dbUnitTest.Employee');
        $this->assertSame('superMan', $row['first_name']);

        try {
            $connection->update('UPDATE dbUnitTest.Employee SET oops = "haha";');
        } catch (\Dao\Exception\WriteException $e) {
            $this->assertContains('Unknown column \'oops\' in \'field list\'', $e->getDetails());
        }
    }
}
?>

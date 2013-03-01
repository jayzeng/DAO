<?php
/**
 * @category testing
 * @package Test_Dao
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 */
namespace Test\Dao;

/**
 * Test information schema class
 * @category testing
 * @package Test_Dao
 */
class InformationSchemaTest extends \PHPUnit_Framework_TestCase
{
    private $_schema;

    public function setUp() {
        $config = array(
                'host'     => DB_HOST,
                'username' => DB_USERNAME,
                'password' => DB_PASSWORD,
                'dbname'   => DB_DBNAME,
                'port'     => DB_PORT,
                'socket'   => DB_SOCKET
                );

        $connection = \Dao\DbFactory::initDatabase($config);
        $this->_schema = new \Dao\MetaData\InformationSchema($connection);
    }

    public function tearDown() {
        unset($this->_schema);
    }

    public function testGetDataBases() {
        $this->assertContains('mysql', $this->_schema->getDatabases());
    }

    public function getSystemTables() {
        return array(
                array('PROCESSLIST'),
                array('COLUMNS'),
                array('SCHEMATA'),
                array('SCHEMA_PRIVILEGES'),
                array('TABLES'),
                array('TABLE_CONSTRAINTS'),
                );
    }

    /**
     * @dataProvider getSystemTables()
     */
    public function testGetTables($systemTable) {
        $tables = $this->_schema->getTables();

        $result = array();
        foreach($tables as $table) {
            $result[] = $table;
            $this->assertNotNull($table);
        }

        $this->assertContains($systemTable, $result);

        $this->assertContains('host', $this->_schema->getTables('mysql'));
        $this->assertFalse($this->_schema->getTables('NoSuchdatabase'));
    }

    public function testHasDatabase() {
        $this->assertFalse($this->_schema->hasDatabase('whatDatabase'));
        $this->assertTrue($this->_schema->hasDatabase('mysql'));
    }

    /**
     * @dataProvider getSystemTables()
     */
    public function testHasTable($systemTable) {
        $this->assertTrue($this->_schema->hasTable($systemTable, 'information_schema'));
        $this->assertFalse($this->_schema->hasTable('bar', 'foo'));
    }

    public function testHasColumn() {
        $this->assertFalse($this->_schema->hasColumn('foo', 'bar', 'baz'));

        $this->assertTrue($this->_schema->hasColumn('SCHEMA_NAME', 'schemata', 'information_schema'));
    }

    public function testGetColumns() {
        $columns = $this->_schema->getColumns('schemata', 'information_schema');

        $mustHaveColumns =
            array(
                    'CATALOG_NAME',
                    'SCHEMA_NAME',
                    'DEFAULT_CHARACTER_SET_NAME',
                    'DEFAULT_COLLATION_NAME',
                    'SQL_PATH'
                 );

        $index = 0;
        foreach($columns as $column) {
            $this->assertEquals($mustHaveColumns[$index], $column['COLUMN_NAME']);
            $index++;
        }

        $this->assertFalse($this->_schema->getColumns('foo', 'bar'));
    }

    public function testGetConstraints() {
        $db = 'mysql';
        foreach($this->_schema->getConstraints('db', $db) as $row) {
            $this->assertSame('PRIMARY KEY', $row['CONSTRAINT_TYPE']);
            $this->assertSame($db, $row['TABLE_SCHEMA']);
        }

        foreach($this->_schema->getConstraints('servers', $db) as $row) {
            $this->assertSame('PRIMARY KEY', $row['CONSTRAINT_TYPE']);
            $this->assertSame($db, $row['TABLE_SCHEMA']);
        }


        $this->assertFalse($this->_schema->getConstraints('foo', 'bar'));
    }

    public function testGetConstraintKeys() {
        $db = 'mysql';
        foreach($this->_schema->getConstraintKeys('db', $db) as $row) {
            $this->assertSame('PRIMARY', $row['CONSTRAINT_NAME']);
            $this->assertSame($db, $row['TABLE_SCHEMA']);
        }

        foreach($this->_schema->getConstraintKeys('servers', $db) as $row) {
            $this->assertSame('PRIMARY', $row['CONSTRAINT_NAME']);
            $this->assertSame($db, $row['TABLE_SCHEMA']);
        }

        $this->assertFalse($this->_schema->getConstraintKeys('foo', 'bar'));
    }

    /**
     * @todo affiliate_network.exchange_rates is probably not a good test input, as it may potentially be removed in the future
     */
    public function testGetTableStatus() {
        $this->assertInternalType('array', $this->_schema->getTableStatus('affiliate_network', 'exchange_rates'));
    }

    /**
     * test GetTableSize
     *
     * @return void
     */
    public function testGetTableSize() {
        $tableInfo = $this->_schema->getTableSize('views', 'information_schema');
        $this->assertNotEmpty( $tableInfo );
        $this->assertInternalType( 'array', $tableInfo );
    }
}
?>

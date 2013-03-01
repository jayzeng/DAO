<?php
namespace Dao\MetaData;

/**
 * @package Dao_MetaData
 */
class InformationSchema
{
    /**
     * Whether we want to include information schema in result set
     *
     * @var Boolean
     */
    protected $_includeInformationSchema = FALSE;

    /**
     * @var \Dao\Adapter\Mysqli\Connection
     */
    protected $_db;

    /**
     * @param \Dao\Adapter\Mysqli\Connection $db
     * @param bool                                    $includeInformationSchema
     */
    public function __construct(\Dao\Adapter\Mysqli\Connection $db, $includeInformationSchema = false) {
        $this->_db = $db;
        $this->_includeInformationSchema = $includeInformationSchema;
    }

    /**
     * @return string[] return all databases within current host
     */
    public function getDatabases() {
        $sql = 'SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA;';
        $result = $this->_db->select($sql);
        $resultSet = array();

        foreach($result as $row) {
            $resultSet[] = $row['SCHEMA_NAME'];
        }

        // Pop off information_schema database
        if(FALSE === $this->_includeInformationSchema) {
            array_shift($resultSet);
        }

        return $resultSet;
    }

    /**
     * Retrieve all tables or all tables from a database if schema is provided
     *
     * @param String|Null $schema database name
     * @return string[]|false The table names or false if none found
     */
    public function getTables($schema = NULL) {
        // If database name is provided, construct a where clause to populate matched fields
        $clause = !isset($schema) ? '' : sprintf('WHERE TABLE_SCHEMA = "%s";', $this->_db->escape($schema));

        $sql = sprintf(
                'SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES %s',
                $clause
                );


        $result = $this->_db->select($sql);

        if(count($result) === 0) {
            return false;
        }

        $resultSet = array();

        foreach($result as $row) {
            $resultSet[] = $row['TABLE_NAME'];
        }

        return $resultSet;
    }

    /**
     * Whether input database exists
     *
     * @param String $schema
     * @return Boolean
     */
    public function hasDatabase($schema) {
        $sql = sprintf('SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "%s";', $schema);

        $row = $this->_db->selectRow($sql);

        if(false === $row) {
            return false;
        }

        return count($row) === 1;
    }

    /**
     * Whether input schema contains table
     *
     * @param String $schema
     * @param String $tableName
     * @return Boolean
     */
    public function hasTable($tableName, $schema) {
        $sql = sprintf(
                'SELECT TABLE_NAME as tableName FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_NAME = "%s" AND TABLE_SCHEMA = "%s";',
                $this->_db->escape($tableName),
                $this->_db->escape($schema)
                );

        $row = $this->_db->selectRow($sql);

        if(false === $row) {
            return false;
        }

        return count($row) === 1;
    }

    /**
     * Whether input database & table contains column
     *
     * @param String $columnName
     * @param String $tableName
     * @param String $databaseName
     * @return Boolean
     */
    public function hasColumn($columnName, $tableName, $databaseName) {
        $sql = sprintf(
                'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = "%s" AND TABLE_SCHEMA = "%s" AND COLUMN_NAME = "%s";',
                $this->_db->escape($tableName),
                $this->_db->escape($databaseName),
                $this->_db->escape($columnName)
                );

        $row = $this->_db->selectRow($sql);

        if(false === $row) {
            return false;
        }

        return count($row) === 1;
    }

    /**
     * Populate all columns from a given table name and database
     *
     * @param String $schema
     * @param String $tableName
     * @return array|false
     */
    public function getColumns($tableName, $schema) {
        $sql = sprintf(
                'SELECT
                TABLE_CATALOG,
                TABLE_SCHEMA,
                TABLE_NAME,
                COLUMN_NAME,
                COLUMN_KEY,
                ORDINAL_POSITION,
                COLUMN_DEFAULT,
                IS_NULLABLE,
                DATA_TYPE,
                CHARACTER_MAXIMUM_LENGTH,
                CHARACTER_OCTET_LENGTH,
                NUMERIC_PRECISION,
                NUMERIC_SCALE,
                CHARACTER_SET_NAME,
                COLLATION_NAME
                FROM INFORMATION_SCHEMA.COLUMNS
                WHERE TABLE_SCHEMA = "%s" AND TABLE_NAME = "%s"',
                $this->_db->escape($schema),
                $this->_db->escape($tableName)
                );

        $rows = $this->_db->select($sql);

        if(count($rows) === 0) {
            return false;
        }

        // Convert from result object to plain array
        $result = array();
        foreach($rows as $row) {
            $result[] =  $row;
        }

        return $result;
    }

    /**
     * @param String $schema
     * @param String $tableName
     * @return array|false
     */
    public function getConstraints($tableName, $schema) {
        $sql = sprintf(
                'SELECT
                CONSTRAINT_CATALOG,
                CONSTRAINT_SCHEMA,
                CONSTRAINT_NAME,
                TABLE_SCHEMA,
                TABLE_NAME,
                CONSTRAINT_TYPE
                FROM INFORMATION_SCHEMA.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = "%s" AND TABLE_NAME = "%s"',
                $this->_db->escape($schema),
                $this->_db->escape($tableName)
                );

        $rows = $this->_db->select($sql);

        if(count($rows) === 0) {
            return false;
        }

        // Convert from result object to plain array
        $result = array();
        foreach($rows as $row) {
            $result[] =  $row;
        }

        return $result;
    }

    /**
     * Return table status in an array, contains information such as engine,
     * create time, update time etc
     *
     * @param String $tableName
     * @param String $schema
     * @return Array|false The row or false if none found
     */
    public function getTableStatus($tableName, $schema) {
        $sql = sprintf('SELECT * FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA ="%s" AND TABLE_NAME = "%s";', $tableName, $schema);

        $row = $this->_db->selectRow($sql);

        return (false !== $row) ? $row : false;
    }

    /**
     * @param String $schema
     * @param String $tableName
     * @return array|false
     */
    public function getConstraintKeys($tableName, $schema) {
        $sql = sprintf(
                'SELECT
                CONSTRAINT_CATALOG,
                CONSTRAINT_SCHEMA,
                CONSTRAINT_NAME,
                TABLE_CATALOG,
                TABLE_SCHEMA,
                TABLE_NAME,
                COLUMN_NAME,
                ORDINAL_POSITION,
                POSITION_IN_UNIQUE_CONSTRAINT,
                REFERENCED_TABLE_SCHEMA,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
                FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = "%s" AND TABLE_NAME = "%s"',
                $this->_db->escape($schema),
                $this->_db->escape($tableName)
                );

        $rows = $this->_db->select($sql);

        if(count($rows) === 0) {
            return false;
        }

        // Convert from result object to plain array
        $result = array();
        foreach($rows as $row) {
            $result[] =  $row;
        }

        return $result;
    }

    /**
     * get Table Size
     *
     * @param string $tableName
     * @param string $schema
     * @return array
     */
    public function getTableSize($tableName, $schema) {
        $sql = sprintf(
                'SELECT
                TABLE_NAME,
                table_rows,
                data_length,
                index_length,
                round(((data_length + index_length) / 1024 / 1024),2) "MB"
                FROM information_schema.TABLES WHERE table_schema = "%s" AND TABLE_NAME = "%s"',
                $this->_db->escape($schema),
                $this->_db->escape($tableName)
                );

        return $this->_db->selectRow($sql);
    }
}
?>

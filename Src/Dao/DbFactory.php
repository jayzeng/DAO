<?php
/**
 * @package Dao
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao;

/**
 * A Db factory object to provide easy access to instantiate database connection object
 *
 * @package Dao
 * @example
 * <pre>
 * To invoke the object:
 * You may populate config from an ini file
 * db.ini
 *
 * [ssd1]
 * host     = 10.1.1.1
 * username = jay
 * password = superpassword
 * dbname   =
 * port     =
 * socket   =
 *
 * We can instantiate a Connection object by populating connection parameters from specified ini
 * file and converts to array.
 *
 * $connection = Dao\DbFactory::initDatabase(
 *      \ConfigReader\Ini::factory('./db.ini','ssd1')->toArray()
 *      );
 *
 * Alternatively, we can directly pass in an associative array
 * $connection = \Dao\DbFactory::initDatabase(
 *                     array(
 *                        'host'     => 'localhost', // required field
 *                        'username' => 'user',      // required field
 *                        'password' => 'password',  // required field
 *                        'dbname'   => '',          // optional field
 *                        'port'     => ''           // optional field
 *                        'socket'   => ''           // optional field
 *                     )
 *      );
 * </pre>
 */
class DbFactory
{
    /**
     * Instantiate connection object or false if connection is not successfully established
     *
     * @param array  $config Connection parameters
     * @param string $driver Default to MYSQLI
     * @return \Dao\Adapter\Mysqli\Connection|string
     */
    public static function initDatabase(array $config, $driver = \Dao\DriverEnum::MYSQLI) {
        $db = new Db();
        $adapter = $db->getAdapter();
        $connection = $adapter->setDriver($driver)->getConnection();
        $connection->setConnectionParams($config);

        try {
            $connection->connect();
            return $connection;
        } catch(Exception\ConnectionException $e) {
            return $e->getMessage(). $e->getDetails();
        }

    }
}
?>

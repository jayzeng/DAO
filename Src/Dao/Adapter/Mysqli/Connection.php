<?php
/**
 * Mysqli Connection object
 * @package Dao_Adapter_Mysqli
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao\Adapter\Mysqli;

/**
  * @TODO consider encrypt db connection or at least db password when preparing for connection parameters
 * @package Dao_Adapter_Mysqli
 * @see http://www.php.net/manual/en/class.mysqli-driver.php
 */
class Connection
{
    /**
     * @var \mysqli|null
     */
    private $_resource = null;

    /**
     * @var Array
     */
    private $_connectionParams = array();

    /**
     * @var \Dao\DriverInterface|null
     */
    private $_driver = null;

    /**
     * @param \Dao\DriverInterface $driver
     * @param Array                         $params
     * @example
     * <pre>
     * $params takes an associative array with the following key name.
     * </pre>
     * <code>
     * array(
     *    'host'     => 'localhost', // required field
     *    'username' => 'user',      // required field
     *    'password' => 'password',  // required field
     *    'dbname'   => '',          // optional field
     *    'port'     => ''           // optional field
     *    'socket'   => ''           // optional field
     * );
     * </code>
     */
    public function __construct(\Dao\DriverInterface $driver, array $params = array()) {
        $this->_driver = $driver;

        if(isset($params)) {
            $this->setConnectionParams($params);
        }
    }

    /**
     * Return mysql resource identifier or false it is not available
     *
     * @return \mysqli|false
     */
    public function getResource() {
        return isset($this->_resource) ? $this->_resource : false;
    }

    /**
     * Shuts down a Sql connection
     *
     * @return Boolean
     */
    public function disconnect() {
        if(isset($this->_resource)) {
            $ret = $this->_resource->close();
            unset($this->_resource);
            return $ret;
        }

        return false;
    }

    /**
     * Exposes connection parameters
     *
     * @return Array
     */
    public function getConnectionParams() {
        return $this->_connectionParams;
    }

    /**
     * Sets connection parameters
     *
     * @param Array $options
     * @return self
     */
    public function setConnectionParams(array $options) {
        $this->_connectionParams = $options;
        return $this;
    }

    /**
     * Provides a lazy init method to connect to the Db
     *
     * @return void
     * @throws \Dao\Exception\ConnectionException
     */
    public function connect() {
        // Exit if we already have a mysqli resource identifier
        if(isset($this->_resource)) return;

        $host = $password = $username = $dbname = $port = $socket = null;

        // Populate values from connection parameters and write them to temp variables declared above

        // Populate required fields
        // @todo add input validations
        //    host - digit.digit.digit.digit
        //    username - alphanumeric
        //    password - alphanumeric + metachars
        //    dbname - alphanumeric
        //    port - numeric
        //    socket - alpha
        foreach(array('host', 'username', 'password') as $param) {
            $$param = $this->_connectionParams[$param];
        }

        // dbname, port and socket are optional, only populate any or all of them if they are provided
        foreach(array('dbname', 'port', 'socket') as $param) {
            if(isset($this->_connectionParams[$param]))
                $$param = $this->_connectionParams[$param];
        }

        // Suppress PHP warnings and let the sanity check below
        // to throw exception if credential is bad
        $this->_resource = @new \mysqli($host,$username,$password,$dbname,(int)$port,$socket);

        if($this->_resource->connect_error) {
            throw new \Dao\Exception\ConnectionException(
                    $this->_resource->connect_error, $this->_resource->connect_errno, $host, $dbname
                    );
        }

    }

    /**
     * Is Sql connection still active?
     *
     * @return Boolean
     */
    public function isConnected() {
        return isset($this->_resource) ? ($this->_resource instanceOf \mysqli) : false;
    }

    /**
     * Escape input parameters for use in a query
     * @todo provides a code snippet example
     *
     * @param String|Array $value, if an array is given, all its elements will be escaped
     * @return String|Array
     */
    public function escape($value) {
        if(is_array($value)) {
            return array_map(array($this, 'escape'), $value);
        }

        $value = (string)$value;

        return $this->_resource->real_escape_string($value);
    }

    /**
     * Properly quotes a value for use in a query
     * @todo provides a code snippet example
     *
     * @param mixed $value The value to quote
     * @return String|array Returns the quoted value
     */
    public function quote ( $value ) {
        if (is_array($value)) {
            return array_map(array($this, 'quote'), $value);
        } else if (is_null($value)) {
            return 'NULL';
        } else if (is_int($value) || is_float($value)) {
            return strval($value);
        }

        return "'". $this->escape($value) ."'";
    }


    /**
     * Prepares a parameterized sql query.
     * Call this method only if you are constructing a parameterized query, use Connection::execute() for raw sql query
     * @todo not finished
     *
     * @param String $query
     * @return Statement
     */
    //public function prepare($query) {
    //$mySqliStmt = $this->_resource->prepare($query);

    //if(false === $mySqliStmt) {
    //throw new \Exception(sprintf('Query %s has errors %s', $query, $this->_resource->error));
    //}

    //$stmtClass = $this->_driver->getStatementClass();

    //$stmt = new $stmtClass($mySqliStmt);
    //return $stmt->setQuery($query);
    //}

    /**
     * Execute a select statement
     *
     * @param String $query
     * @return \Dao\Adapter\Mysqli\Result|true instance of result class if query returns results, or true if query type does not return results
     * @throws \Dao\Exception\ReadException if query failed
     */
    public function select( $query ) {
        $resultClass = $this->_driver->getResultClass();
        $queryResult = $this->_resource->query($query);

        $result = false;
        if($queryResult instanceOf \mysqli_result) {
            $result = new $resultClass($queryResult);
        }

        if(false === $result) {
            // Throw an read exception
            throw new \Dao\Exception\ReadException(
                    $this->_resource->error,     // Internal error message
                    $this->_resource->errno,     // Internal error code
                    $query                       // Sql query
                    );
        }

        return $result;
    }

    /**
     * Return the 1st row of a query
     *
     * @param String $query
     * @return Array|false false if no result is returned
     * @throws \Dao\Exception\ReadException
     */
    public function selectRow($query) {
        $result = $this->select($query);

        // Return false if no result is returned
        if(count($result) === 0) {
            return false;
        }

        foreach($result as $row) {
            return $row;
        }
    }

    /**
     * Execute an update statement. Return true is query is successfully updated,
     * or throws a WriteException
     *
     * @param String $query
     * @return Boolean
     * @throws \Dao\Exception\WriteException
     */
    public function update($query) {
        if($this->_resource->query($query)) {
            return $this->_resource->affected_rows > 0 ? true : false;
        }

        throw new \Dao\Exception\WriteException(
                $this->_resource->error, $this->_resource->errno, $query
                );
    }

    /**
     * Execute an insert statement.
     * Return last insert id if query is successfully inserted, or throw a write exception
     *
     * @param String $query
     * @return Int|false return last insert id if operation is successful or return false
     * @throws \Dao\Exception\WriteException
     */
    public function insert($query) {
        if($this->_resource->query($query)) {
            return $this->_resource->affected_rows > 0 ? $this->_resource->insert_id : false;
        }

        throw new \Dao\Exception\WriteException(
                $this->_resource->error, $this->_resource->errno, $query
                );
    }

    /**
     * Execute a delete statement
     * Return true is query is successfully updated, or throws a QueryException
     *
     * @param String $query
     * @return Boolean
     * @throws \Dao\Exception\DeleteException
     */
    public function delete($query) {
        if($this->_resource->query($query)) {
            return $this->_resource->affected_rows > 0 ? true : false;
        }

        throw new \Dao\Exception\DeleteException(
                $this->_resource->error, $this->_resource->errno, $query
                );
    }

    /**
     * Retrieves MySQL server version
     *
     * @return string
     */
    public function getServerVersion() {
        $version = $this->_resource->server_version;
        return sprintf('%d.%d.%d',
                (int) ($version / 10000),
                (int) ($version % 10000 / 100),
                (int) ($version % 100)
                );
    }

    /**
     * Return error message and code from the previously sql statement
     *
     * @return Array|false
     */
    public function getError() {
        // If resource identifier is not set, return false
        if(!$this->_resource) {
            return false;
        }

        return array(
                'errorMessage' => $this->_resource->error,
                'errorNumber'  => $this->_resource->errno
                );
    }
}
?>

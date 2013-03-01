<?php
/**
 * @package Dao_Adapter_Mysqli
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao\Adapter\Mysqli;
use Dao\Db;

/**
 * @todo this object is not being used.
 * Will come back when we need to have parameterized SQL query
 *
 * @package Dao_Adapter_MySqli
 */
class Statement
{
    /**
     * @var \mysqli_stmt
     */
    private $_stmt = null;

    /**
     * SQL query it is executing
     *
     * @var String|null
     */
    private $_query = null;

    /**
     * @var String|null
     */
    private $_fetchMode;

    /**
     * @TODO unknown (not implemented)
     */
    private $_result;

    /**
     * @param \mysqli_stmt $stmt
     */
    public function __construct(\mysqli_stmt $stmt) {
        $this->_stmt = $stmt;
    }

    /**
     * Close the cursor and statement
     *
     * @return Boolean
     */
    public function close() {
        if($this->_stmt) {
            $ret = $this->_stmt->close();
            unset($this->_stmt);
            return $ret;
        }
        return false;
    }

    /**
     * @return String|null
     */
    public function getQuery() {
        return $this->_query;
    }

    /**
     * Returns resource identifier
     *
     * @return \mysqli_stmt
     */
    public function getResource() {
        return $this->_stmt;
    }

    /**
     * @param String $query
     * @return self
     */
    public function setQuery($query) {
        $this->_query = $query;
        return $this;
    }

    public function bindParameter($position, $type, $value) {
        //$this->_
    }

    public function setFetchMode($fetchMode) {
        $this->_fetchMode = $fetchMode;
        return $this;
    }

    public function getFetchMode() {
        return $this->_fetchMode;
    }

    public function fetch($fetchMode = null) {
        $mode = isset($fetchMode) ? $fetchMode : $this->_fetchMode;

        switch($mode) {
            case Db::FETCH_ASSOC:
            default:
                return $this->_result->fetch_assoc();
            case Db::FETCH_ALL:
                return $this->_result->fetch_all();
            case Db::FETCH_OBJECT:
                return $this->_result->fetch_object();
            case Db::FETCH_ROW:
                return $this->_result->fetch_row();
        }
    }

    /**
     * Binds an array of parameters
     */
    public function bindParameters(array $parameters) {

    }

    public function execute($query) {
        $this->_stmt->execute($query);
    }
}
?>

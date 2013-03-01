<?php
/**
 * Mysqli Connection object
 * @package Dao_Adapter_Mysqli
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao\Adapter\Mysqli;

/**
  * An mysqli result wrapper
 * @package Dao_Adapter_Mysqli
 * @see http://www.php.net/manual/en/class.mysqli-driver.php
 */
class Result implements \Iterator, \Countable
{
    /**
     * @link http://www.php.net/manual/en/class.mysqli-result.php
     *
     * @var \mysqli_result|null
     */
    protected $_result = null;

    /**
     * @var Int
     */
    protected $_numberOfRows = -1;

    /**
     * @var Int
     */
    protected $_processedRows = 0;

    /**
     * @var array
     */
    protected $_mysqlResult = array();

    /**
     * @var Int
     */
    protected $_pointer = 0;

    /**
     * @var Boolean
     */
    protected $_moveToNextRow = false;

    /**
     * @param \mysqli_result $result
     */
    public function __construct(\mysqli_result $result) {
        $this->_result = $result;
        $this->_numberOfRows = $result->num_rows;
    }

    /**
     * @see \Iterator::current()
     *
     * @return mixed
     * @throws \OutOfRangeException
     */
    public function current() {
        // Seems overkill to define our own OutOfRangeException. Use default in this case
        if ($this->_pointer > ($this->_numberOfRows - 1)) {
            throw new \OutOfRangeException(
                    'Attempting to access a row that is outside of the number of rows in this result.'
                    );
        }

        $pointer = $this->_pointer;

        if (!array_key_exists($this->_pointer, $this->_mysqlResult)) {
            $this->_mysqlResult[$this->_pointer] = $this->_result->fetch_array(\MYSQLI_ASSOC);
            $this->_pointer++;
            $this->_moveToNextRow = true;
            $this->_processedRows++;

            // Free up results when there is no more row to process
            if ($this->_processedRows === $this->_numberOfRows) {
                $this->_result->free();
            }
        }

        return $this->_mysqlResult[$pointer];
    }

    /**
     * @see \Iterator::next()
     */
    public function next() {
        if ($this->_moveToNextRow == false) {
            $this->_pointer++;
        }
        $this->_moveToNextRow = false;
    }

    /**
     * @see \Iterator::key()
     */
    public function key() {
        return $this->_pointer;
    }

    /**
     * @see \Iterator::rewind()
     */
    public function rewind() {
        $this->_pointer = 0;
        $this->_result->data_seek(0);
    }


    /**
     * @see \Iterator::valid()
     */
    public function valid() {
        return ($this->_pointer< $this->_numberOfRows);
    }

    /**
     * @see \Countable::count()
     * @return Int return the number of result from a given query
     */
    public function count() {
        return $this->_numberOfRows;
    }

    /**
     * @return Int return the number of result from a given query
     */
    public function getNumberOfRows() {
        return $this->_numberOfRows;
    }
}
?>

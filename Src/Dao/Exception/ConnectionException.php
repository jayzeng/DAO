<?php
/**
 * @package Dao_Exception
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao\Exception;

/**
 * @package Dao_Exception
 */
class ConnectionException extends AbstractException
{
    /**
     * The host of the database server
     *
     * @var String
     */
    private $_host;

    /**
     * The database trying to be connected to
     *
     * @var String
     */
    private $_database;

    /**
     * Constructor
     *
     * @param String  $message
     * @param Integer $code
     * @param String  $host
     * @param String  $database
     */
    public function __construct($message, $code, $host, $database) {
        parent::__construct($message, $code);
        $this->_host = $host;
        $this->_database = $database;
    }

    /**
     * @see DbExceptionInterface::getDetails()
     */
    public function getDetails() {
        return $this->formatException(
                sprintf('Host: %s %sDataBase: %s',
                    $this->_host,
                    PHP_EOL,
                    isset($this->_database) ? $this->_database : 'Database is not provided'
                    ).PHP_EOL
                );
    }
}
?>

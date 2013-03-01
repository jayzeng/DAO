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
class QueryException extends AbstractException implements DbExceptionInterface
{
    /**
     * The query that caused the error
     *
     * @var String|null
     */
    private $_query;

    /**
     * Constructor
     *
     * @param String       $message Error message
     * @param Integer|null $code Error code
     * @param String|null  $query The sql query that caused the error
     */
    public function __construct ( $message, $code = null, $query = null ) {
        parent::__construct( $message, $code );
        $this->_query = $query;
    }

    /**
     * @see \Dao\Exception\DbExceptionInterface::getDetails()
     */
    public function getDetails () {
        return sprintf(
                '
                Error message: %s
                Error Code: %s
                Sql Query: %s',
                $this->message,
                $this->code,
                $this->_query
                ).PHP_EOL;
    }
}
?>

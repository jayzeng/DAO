<?php
/**
 * Builds up the relationship to pull out various Mysqli object
 * @package Dao_Adapter
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao\Adapter;

/**
 * @package Dao_Exception
 */
class Mysqli implements \Dao\DriverInterface
{
    /**
     * @var String
     */
    protected $connectionClass = 'Dao\Adapter\Mysqli\Connection';

    /**
     * @var String
     */
    protected $statementClass  = 'Dao\Adapter\Mysqli\Statement';

    /**
     * @var String
     */
    protected $resultClass     = 'Dao\Adapter\Mysqli\Result';

    /**
     * @param String $connectionClass
     * @return string the connection class passed in
     */
    public function setConnectionClass($connectionClass) {
        return $this->connectionClass = $connectionClass;
    }

    /**
     * @return String Connection class
     */
    public function getConnectionClass() {
        return $this->connectionClass;
    }

    /**
     * @param String $resultClass
     * @return string the result class passed in
     */
    public function setResultClass($resultClass) {
        return $this->resultClass = $resultClass;
    }

    /**
     * @return String Result class
     */
    public function getResultClass() {
        return $this->resultClass;
    }

    /**
     * @param String $statementClass
     * @todo Temporarily commented out, uncomment when we implement parameterized queries
     * @return string the statement class passed in
     */
    //public function setStatementClass($statementClass) {
        //return $this->statementClass = $statementClass;
    //}

    /**
     * @todo Temporarily commented out, uncomment when we implement parameterized queries
     * @return String statement class
     */
    //public function getStatementClass() {
        //return $this->statementClass;
    //}
}
?>

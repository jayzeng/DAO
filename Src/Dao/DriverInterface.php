<?php
/**
 * @package Dao
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao;

/**
 * @package Dao
 */
interface DriverInterface
{
    /**
     * Return Connection class
     *
     * @return String
     */
    public function getConnectionClass();

    /**
     * Return Result class
     *
     * @return String
     */
    public function getResultClass();

    /**
     * Return statement class
     * @todo Temporarily commented out, uncomment when we implement parameterized queries
     *
     * @return String
     */
    //public function getStatementClass();
}
?>

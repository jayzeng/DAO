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
interface DbExceptionInterface
{
    /**
     * Returns detailed information about the exception
     *
     * @return String
     */
     public function getDetails();
}

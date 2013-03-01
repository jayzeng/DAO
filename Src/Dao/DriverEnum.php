<?php
/**
 * A list of drivers we currently support
 * @package Dao
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @since 12/08/2011
 * @version 0.1
 */
namespace Dao;
use \Util\Enum;

/**
 * @package Dao
 */
class DriverEnum extends Enum
{
    const MYSQLI = 'Mysqli';
    //const PDO = 'Pdo';
}
?>

<?php
/**
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao;

/**
 * @package Dao
 */
class Db
{
    /**
     * Default adapter to be used
     *
     * @var string
     */
    private $_adapter = '\Dao\Adapter';

    /**
     * Empty constructor
     */
    public function __construct() {}

    /**
     * Sets an adapter
     *
     * @param String $adapter
     * @return self
     */
    public function setAdapter($adapter) {
        $this->_adapter = $adapter;
        return $this;
    }

    /**
     * Return an adapter instance
     *
     * @return \Dao\Adapter adapter to be used
     * @throws \InvalidArgumentException
     */
    public function getAdapter() {
        if( class_exists($this->_adapter) ) {
            return new $this->_adapter;
        }

        throw new \InvalidArgumentException( "Class {$this->_adapter} does not exist");
    }
}
?>

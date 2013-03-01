<?php
/**
 * Adapter pattern
 * @subpackage Exception
 * @author Jay Zeng (jayzeng@jay-zeng.com)
 * @version 0.1
 */
namespace Dao;

/**
 * @category Dao
 */
class Adapter
{
    /**
     * @var String
     */
    private $_driver;

    /**
     * Sets driver to populate the right adapter
     *
     * @param String $driver
     * @return self
     */
    public function setDriver($driver) {
        $this->_driver = $driver;
        return $this;
    }

    /**
     * Return Sql driver
     *
     * @return String
     */
    public function getDriver() {
        return $this->_driver;
    }

    /**
     * Return an adapter instance or false if adapter class does not exist
     *
     * @TODO: convention has this returning an Adapter but there is no guarantee
     * @return \Dao\DriverInterface|false
     */
    public function getDriverAdapter() {
        $className = sprintf('Dao\Adapter\%s', $this->_driver);

        if(class_exists($className)) {
            return new $className();
        }
        return false;
    }

    /**
     * @param Array $options
     * @return \Dao\Adapter\Mysqli\Connection
     * @throws \Dao\Exception\RuntimeException
     */
    public function getConnection(array $options = array()) {
        $driverAdapter = $this->getDriverAdapter();

        if($driverAdapter) {
            $className = $driverAdapter->getConnectionClass();

            if(class_exists($className)) {
                return new $className($driverAdapter, $options);
            }
        }

        // Retrieves a list of supported drivers, delimitered with comma
        $supportedDrivers = new DriverEnum();

        throw new Exception\RuntimeException(
                sprintf(
                    'Driver %s is not supported. Supported drivers are: %s',
                    $this->_driver,
                    join(',',$supportedDrivers->getValues())
                    )
                );
    }
}
?>

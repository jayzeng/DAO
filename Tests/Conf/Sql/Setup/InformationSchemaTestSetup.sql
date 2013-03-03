CREATE DATABASE IF NOT EXISTS superFoo;

use superFoo;

--
-- Table structure for table `awesomo`
--

DROP TABLE IF EXISTS `awesomo`;
CREATE TABLE `awesomo` (
  `currency` char(3) NOT NULL DEFAULT '',
  `rate` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`currency`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

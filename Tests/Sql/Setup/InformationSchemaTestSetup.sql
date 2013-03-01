CREATE DATABASE IF NOT EXISTS affiliate_network;

use affiliate_network;

DROP TABLE IF EXISTS `Task_Queue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Task_Queue` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `Brands_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `name` varchar(30) NOT NULL,
  `status` enum('pending','locked','processed','failed') DEFAULT 'pending',
  `created` datetime NOT NULL,
  `modified` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`Brands_id`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=39744 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `api_logs`
--

DROP TABLE IF EXISTS `api_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `api_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `request_id` varchar(200) NOT NULL,
  `Brands_id` smallint(6) DEFAULT NULL,
  `target` varchar(100) DEFAULT NULL,
  `method` varchar(100) DEFAULT NULL,
  `arguments` text,
  `status` tinyint(4) DEFAULT '0',
  `error` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `line` smallint(5) unsigned DEFAULT NULL,
  `backtrace` text,
  `version` tinyint(3) unsigned DEFAULT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `referer` varchar(255) DEFAULT NULL,
  `request_time` decimal(10,6) DEFAULT NULL,
  `peak_memory_usage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `request_id` (`request_id`)
) ENGINE=MyISAM AUTO_INCREMENT=959951 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_api_endpoint_servers`
--

DROP TABLE IF EXISTS `app_api_endpoint_servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_api_endpoint_servers` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `app_pool_id` tinyint(3) unsigned NOT NULL,
  `host` varchar(100) NOT NULL,
  `internal_ip` varchar(100) NOT NULL,
  `state` enum('alive','dead') NOT NULL DEFAULT 'alive',
  `evn` enum('dev','stage','prod','prod-aws') NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `group` enum('a','b') NOT NULL,
  `has_memcache` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_api_endpoints`
--

DROP TABLE IF EXISTS `app_api_endpoints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_api_endpoints` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `app_pool_id` tinyint(3) unsigned NOT NULL,
  `state` enum('alive','dead') NOT NULL DEFAULT 'alive',
  `hostname` varchar(100) NOT NULL,
  `evn` enum('dev','stage','prod','prod-aws') NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `has_ssl` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `app_pools`
--

DROP TABLE IF EXISTS `app_pools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `app_pools` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  `status` enum('alive','dead','repaired') DEFAULT 'alive',
  `is_default` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_billing`
--

DROP TABLE IF EXISTS `brand_billing`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_billing` (
  `brand_id` smallint(6) unsigned NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `company` varchar(100) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `region` varchar(50) DEFAULT NULL,
  `country` varchar(50) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `payment_option` enum('none','monthly','monthly_year_commit','annual_payment') NOT NULL DEFAULT 'monthly',
  `amount` decimal(6,2) NOT NULL,
  `trial_amount` decimal(6,2) NOT NULL DEFAULT '0.00',
  `trial_occurrences` smallint(6) unsigned NOT NULL DEFAULT '0',
  `card_number` varchar(32) NOT NULL,
  `card_type` char(10) NOT NULL,
  `expiration_month` char(2) NOT NULL,
  `expiration_year` char(4) NOT NULL,
  `security_code` char(10) NOT NULL,
  `ip_address` varchar(15) DEFAULT NULL,
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `payment_method` enum('authorize.net','paypal') NOT NULL DEFAULT 'authorize.net',
  `subscription_id` varchar(30) DEFAULT NULL,
  `pay_date` date DEFAULT NULL,
  `profile_transferred` tinyint(1) NOT NULL DEFAULT '0',
  `updated_profile_id` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10169 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_creators`
--

DROP TABLE IF EXISTS `brand_creators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_creators` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` smallint(5) unsigned NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL,
  `salt` varchar(32) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12513 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_directory`
--

DROP TABLE IF EXISTS `brand_directory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_directory` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` smallint(5) unsigned NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=56 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_dns_test`
--

DROP TABLE IF EXISTS `brand_dns_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_dns_test` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `batch` smallint(5) unsigned DEFAULT NULL,
  `brand_id` smallint(5) unsigned DEFAULT NULL,
  `hostname` varchar(100) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `cname` varchar(100) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=20220 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_editions`
--

DROP TABLE IF EXISTS `brand_editions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_editions` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` smallint(6) unsigned NOT NULL,
  `CRM_Employees_id` smallint(6) NOT NULL DEFAULT '0',
  `edition` enum('free','basic','pro','enterprise','dedicated') NOT NULL DEFAULT 'free',
  `status` enum('active','disabled','fraud','deleted','test','archived','paused') NOT NULL DEFAULT 'active',
  `CRM_Products_id` smallint(6) unsigned NOT NULL,
  `payment_cycle` enum('monthly','monthly_contract','annual','annual_contract') NOT NULL DEFAULT 'monthly',
  `payment_price` decimal(6,2) NOT NULL DEFAULT '0.00',
  `contract_term` smallint(6) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_invoice_date` date DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11384 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_flags`
--

DROP TABLE IF EXISTS `brand_flags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_flags` (
  `brand_id` smallint(5) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`brand_id`,`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_hostnames`
--

DROP TABLE IF EXISTS `brand_hostnames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_hostnames` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` smallint(5) unsigned NOT NULL,
  `domain` varchar(50) NOT NULL,
  `type` enum('jump') NOT NULL,
  `status` enum('active','deleted') NOT NULL DEFAULT 'active',
  `jump_hostname_has_ssl` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `brand_id` (`brand_id`)
) ENGINE=MyISAM AUTO_INCREMENT=838 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_information`
--

DROP TABLE IF EXISTS `brand_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_information` (
  `brand_id` smallint(6) unsigned NOT NULL,
  `company` varchar(100) NOT NULL,
  `address1` varchar(255) NOT NULL,
  `address2` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `region` varchar(50) DEFAULT NULL,
  `country` varchar(50) NOT NULL,
  `zipcode` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `valid_from` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `valid_to` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`brand_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_ips`
--

DROP TABLE IF EXISTS `brand_ips`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_ips` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` smallint(6) unsigned NOT NULL,
  `ip_address` varchar(255) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `brand_ip` (`brand_id`,`ip_address`)
) ENGINE=MyISAM AUTO_INCREMENT=1923 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_survey`
--

DROP TABLE IF EXISTS `brand_survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_survey` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `brand_id` smallint(6) unsigned NOT NULL,
  `question` varchar(50) NOT NULL,
  `answer` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10889 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_verify`
--

DROP TABLE IF EXISTS `brand_verify`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_verify` (
  `hash` varchar(255) NOT NULL DEFAULT '',
  `data` varchar(255) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brand_vm_instances`
--

DROP TABLE IF EXISTS `brand_vm_instances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brand_vm_instances` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `host_id` int(11) NOT NULL,
  `brand_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq` (`brand_id`,`host_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `network_id` varchar(20) NOT NULL,
  `network_name` varchar(40) DEFAULT NULL,
  `hostname` varchar(50) DEFAULT NULL,
  `jump_hostname` varchar(50) DEFAULT NULL,
  `dev_hostname` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL DEFAULT '',
  `created` datetime DEFAULT NULL,
  `has_verified_email` tinyint(1) DEFAULT NULL,
  `has_database` tinyint(1) NOT NULL DEFAULT '0',
  `has_user` tinyint(1) NOT NULL DEFAULT '0',
  `is_current` tinyint(1) NOT NULL DEFAULT '0',
  `schema_version` smallint(5) unsigned DEFAULT '1',
  `code_version` smallint(5) unsigned DEFAULT NULL,
  `shared_server` tinyint(1) NOT NULL DEFAULT '1',
  `activated` datetime DEFAULT NULL,
  `network_type` enum('affiliate_network','affiliate','affiliate_becoming_network','advertiser','super_affiliate','publisher','network','agency','developer','unknown') NOT NULL DEFAULT 'unknown',
  `edition` enum('free','basic','pro','enterprise','dedicated','developer') DEFAULT 'free',
  `payment_cycle` enum('monthly','monthly_contract','annual') NOT NULL DEFAULT 'monthly',
  `payment_price` decimal(6,2) NOT NULL DEFAULT '0.00',
  `status` enum('active','disabled','fraud','deleted','test','archived','paused') NOT NULL DEFAULT 'active',
  `has_offer` tinyint(1) NOT NULL DEFAULT '0',
  `jump_hostname_has_ssl` tinyint(1) NOT NULL DEFAULT '0',
  `volume_index` int(11) NOT NULL DEFAULT '0',
  `under_maintenance` tinyint(1) DEFAULT '0',
  `disable_tracking_monitor` tinyint(1) DEFAULT '0',
  `exchange_enabled` tinyint(1) DEFAULT '0',
  `reactivated_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `referral_type` enum('friend','email','google','ad_internet','ad_print','conference','network','other') DEFAULT NULL,
  `referral_details` varchar(255) DEFAULT NULL,
  `is_archived` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `stats_version` tinyint(4) NOT NULL DEFAULT '2',
  `db_pool_id` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `db_stat_pool_id` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `data_on_db_pool` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `app_pool_id` tinyint(3) unsigned NOT NULL DEFAULT '5',
  `max_partition_date` date DEFAULT NULL,
  `memcache_cluster` char(4) DEFAULT NULL,
  `ssh_port_start` smallint(5) unsigned DEFAULT NULL,
  `signup_code` varchar(255) DEFAULT NULL,
  `modified` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `qproc_stats_target` enum('brand_db','stat_db','both_dbs') NOT NULL DEFAULT 'brand_db',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`network_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12812 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `browser_groups`
--

DROP TABLE IF EXISTS `browser_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `browser_groups` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(40) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  `class` enum('desktop','mobile') DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `browsers`
--

DROP TABLE IF EXISTS `browsers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `browsers` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `bit_pos` smallint(5) unsigned NOT NULL,
  `group_id` smallint(5) unsigned DEFAULT NULL,
  `display_name` varchar(40) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `content_optimization_items`
--

DROP TABLE IF EXISTS `content_optimization_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `content_optimization_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_name` varchar(20) DEFAULT NULL,
  `item_key` varchar(60) DEFAULT NULL,
  `item_value` varchar(60) DEFAULT NULL,
  `combo_entry` tinyint(1) NOT NULL DEFAULT '0',
  `impressions` int(11) NOT NULL DEFAULT '0',
  `clicks` int(11) NOT NULL DEFAULT '0',
  `conversions` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `base` (`test_name`,`item_key`,`item_value`)
) ENGINE=MyISAM AUTO_INCREMENT=554 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `conversion_ad_ids`
--

DROP TABLE IF EXISTS `conversion_ad_ids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `conversion_ad_ids` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` smallint(6) unsigned NOT NULL,
  `ad_id` varchar(30) NOT NULL,
  `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `brand_ad_id` (`brand_id`,`ad_id`)
) ENGINE=MyISAM AUTO_INCREMENT=303843 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `code` char(3) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `id` smallint(6) NOT NULL DEFAULT '0',
  `paypal_code` char(3) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `country_code_name` (`code`,`name`),
  UNIQUE KEY `code` (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_pools`
--

DROP TABLE IF EXISTS `db_pools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_pools` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  `status` enum('alive','dead','repaired') DEFAULT 'alive',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_servers`
--

DROP TABLE IF EXISTS `db_servers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_servers` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `db_pool_id` tinyint(3) unsigned NOT NULL,
  `host` varchar(100) NOT NULL,
  `internal_host` varchar(100) NOT NULL,
  `port` smallint(5) unsigned NOT NULL DEFAULT '3306',
  `role` enum('master','slave','stats','olap') NOT NULL,
  `state` enum('alive','dead') NOT NULL DEFAULT 'alive',
  `evn` enum('dev','stage','prod','prod-aws') NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `position` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `db_stat_pools`
--

DROP TABLE IF EXISTS `db_stat_pools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `db_stat_pools` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `master_host` varchar(64) NOT NULL,
  `master_port` int(10) unsigned NOT NULL,
  `slave_host` varchar(64) NOT NULL,
  `slave_port` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `environment`
--

DROP TABLE IF EXISTS `environment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `environment` (
  `name` varchar(255) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `exchange_rates`
--

DROP TABLE IF EXISTS `exchange_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exchange_rates` (
  `currency` char(3) NOT NULL DEFAULT '',
  `rate` float NOT NULL DEFAULT '0',
  PRIMARY KEY (`currency`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `fake_brands`
--

DROP TABLE IF EXISTS `fake_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fake_brands` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `network_id` varchar(100) NOT NULL,
  `network_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`network_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25596 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `jurisdiction` bigint(20) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `jurisdiction` (`jurisdiction`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `model_caches`
--

DROP TABLE IF EXISTS `model_caches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_caches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `model_name` varchar(128) NOT NULL,
  `app_pool_id` tinyint(3) unsigned NOT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_unique_key` (`model_name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pricing_plan_defaults`
--

DROP TABLE IF EXISTS `pricing_plan_defaults`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pricing_plan_defaults` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `edition` enum('free','basic','pro','enterprise','dedicated') NOT NULL DEFAULT 'free',
  `payment_cycle` enum('monthly','monthly_contract','annual') NOT NULL DEFAULT 'monthly',
  `payment_price` decimal(6,2) NOT NULL DEFAULT '0.00',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `regions`
--

DROP TABLE IF EXISTS `regions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `regions` (
  `country_code` char(2) DEFAULT NULL,
  `country_code_3c` char(3) DEFAULT NULL,
  `region_code` char(16) DEFAULT NULL,
  `region_name` char(32) DEFAULT NULL,
  `region_num` int(11) DEFAULT NULL,
  `bit_pos` tinyint(4) NOT NULL,
  UNIQUE KEY `country_region_num` (`country_code_3c`,`region_num`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sandbox_keys`
--

DROP TABLE IF EXISTS `sandbox_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sandbox_keys` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `brand_id` smallint(5) unsigned NOT NULL,
  `key` varchar(32) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used` timestamp NULL DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `skeleton_keys`
--

DROP TABLE IF EXISTS `skeleton_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `skeleton_keys` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `value` varchar(32) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `used` timestamp NULL DEFAULT NULL,
  `network_id` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `ip_address` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `value` (`value`)
) ENGINE=MyISAM AUTO_INCREMENT=56777 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tax_rates_wa`
--

DROP TABLE IF EXISTS `tax_rates_wa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tax_rates_wa` (
  `zipcode` int(5) unsigned NOT NULL,
  `plus4_low` int(4) unsigned NOT NULL,
  `plus4_high` int(4) unsigned NOT NULL,
  `code` int(4) unsigned NOT NULL,
  `state` decimal(6,5) NOT NULL DEFAULT '0.00000',
  `local` decimal(6,5) NOT NULL DEFAULT '0.00000',
  `tot_rate` decimal(6,5) NOT NULL DEFAULT '0.00000',
  `date_start` date NOT NULL,
  `date_end` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `timezones`
--

DROP TABLE IF EXISTS `timezones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timezones` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `timezone_location` varchar(64) NOT NULL DEFAULT '',
  `gmt` varchar(11) NOT NULL DEFAULT '',
  `keyword` varchar(60) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=103 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vm_hosts`
--

DROP TABLE IF EXISTS `vm_hosts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vm_hosts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `host` varchar(100) NOT NULL,
  `alias` varchar(100) DEFAULT NULL,
  `status` enum('alive','dead','repaired') DEFAULT 'alive',
  `pool_id` int(11) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `vm_pools`
--

DROP TABLE IF EXISTS `vm_pools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vm_pools` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(30) NOT NULL,
  `status` enum('alive','dead','repaired') DEFAULT 'alive',
  `display` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- Dump completed on 2012-08-31 19:06:52

# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: localhost (MySQL 5.6.38-log)
# Database: slackbot
# Generation Time: 2018-04-16 12:15:12 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table invoices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `invoices`;

CREATE TABLE `invoices` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `address` text,
  `address_label` text,
  `description` text,
  `currency` varchar(4) DEFAULT NULL,
  `price` decimal(17,9) unsigned DEFAULT NULL,
  `balance` decimal(17,9) unsigned DEFAULT NULL,
  `is_completed` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `original_price` decimal(17,9) unsigned DEFAULT NULL,
  `original_currency` varchar(4) DEFAULT NULL,
  `notification_id` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;

INSERT INTO `invoices` (`id`, `address`, `address_label`, `description`, `currency`, `price`, `balance`, `is_completed`, `original_price`, `original_currency`, `notification_id`)
VALUES
	(13,'2MxJWBC89Mrsn79m5auA6GaNr41jEP5DNVd','thisisatest001342355BTCon15042018192636','this is a test 0.01342355BTC','0',0.013423550,0.028000000,1,NULL,NULL,'e0600222951e74fd7c41d4ee');

/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table user_invoices
# ------------------------------------------------------------

DROP TABLE IF EXISTS `user_invoices`;

CREATE TABLE `user_invoices` (
  `user_id` int(10) unsigned NOT NULL,
  `invoice_id` int(10) unsigned DEFAULT NULL,
  `invioce_type` tinyint(1) unsigned DEFAULT NULL,
  `callback_url` text,
  `trigger_id` text,
  UNIQUE KEY `userInvoices` (`invoice_id`,`user_id`),
  KEY `invoiceId` (`invoice_id`),
  KEY `userId` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `user_invoices` WRITE;
/*!40000 ALTER TABLE `user_invoices` DISABLE KEYS */;

INSERT INTO `user_invoices` (`user_id`, `invoice_id`, `invioce_type`, `callback_url`, `trigger_id`)
VALUES
	(7,13,1,'https://hooks.slack.com/commands/TA477N8BS/345725975829/tz2wsgFy5G6WowNsTaKT0E2H','b5a0aab45e2740485b5b1d74cf786e78');

/*!40000 ALTER TABLE `user_invoices` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table users
# ------------------------------------------------------------

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(12) DEFAULT '',
  `user_name` text,
  `channel_id` text,
  `channel_name` text,
  `team_id` text,
  PRIMARY KEY (`id`),
  KEY `userId` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;

INSERT INTO `users` (`id`, `user_id`, `user_name`, `channel_id`, `channel_name`, `team_id`)
VALUES
	(7,'UA4TRF8MQ','davarashvili','CA52C4X6Z','blockchain','TA477N8BS');

/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

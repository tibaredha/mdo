-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.5.20-log - MySQL Community Server (GPL)
-- Server OS:                    Win32
-- HeidiSQL version:             7.0.0.4053
-- Date/time:                    2012-04-17 00:27:12
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET FOREIGN_KEY_CHECKS=0 */;

-- Dumping database structure for mvc
CREATE DATABASE IF NOT EXISTS `mvc` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `mvc`;


-- Dumping structure for table mvc.data
CREATE TABLE IF NOT EXISTS `data` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `text` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=28 DEFAULT CHARSET=latin1;

-- Dumping data for table mvc.data: 5 rows
/*!40000 ALTER TABLE `data` DISABLE KEYS */;
INSERT INTO `data` (`id`, `text`) VALUES
	(21, 'test'),
	(22, 'test2'),
	(23, 'test3'),
	(24, 'test4'),
	(25, 'test5'),
	(26, 'test'),
	(27, 'test2333');
/*!40000 ALTER TABLE `data` ENABLE KEYS */;


-- Dumping structure for table mvc.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `password` varchar(64) NOT NULL,
  `role` enum('default','admin','owner') NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

-- Dumping data for table mvc.user: 2 rows
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `login`, `password`, `role`) VALUES
	(1, 'jesse', '3327a2154aa1900fa110ae3d20d27d051ba719ead0396f1a23d6865b2677ed4a', 'owner'),
	(2, 'test2', 'fde4ae29a847f01fba59761070a6c2008ba9746b67bfccf960233126b23d11cc', 'default');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
/*!40014 SET FOREIGN_KEY_CHECKS=1 */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

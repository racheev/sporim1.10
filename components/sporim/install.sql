DROP TABLE IF EXISTS `#__sporim`;
CREATE TABLE `#__sporim` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `description` text,
  `keywords` varchar(255) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `da` int(11) NOT NULL,
  `no` int(11) NOT NULL,
  `published` tinyint(1) NOT NULL,
  `raz` int(11) NOT NULL,
  `pubdate` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `#__sporim_rate`;
CREATE TABLE `#__sporim_rate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `s_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `s_id` (`s_id`),
  KEY `user_id` (`user_id`),
  KEY `date` (`date`),
  KEY `time` (`time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `#__sporim` ADD `keywords` VARCHAR( 255 ) NOT NULL AFTER `description`

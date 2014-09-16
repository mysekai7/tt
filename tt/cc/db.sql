/*
SQLyog Enterprise - MySQL GUI v6.56
MySQL - 5.1.38-community : Database - cc
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

CREATE DATABASE /*!32312 IF NOT EXISTS*/`cc` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `cc`;

/*Table structure for table `comments` */

DROP TABLE IF EXISTS `comments`;

CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `album_id` int(10) unsigned NOT NULL DEFAULT '0',
  `photo_id` int(10) unsigned NOT NULL DEFAULT '0',
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `username` varchar(45) NOT NULL DEFAULT '',
  `email` varchar(200) NOT NULL DEFAULT '',
  `message` varchar(255) NOT NULL DEFAULT '',
  `type` enum('posts','reply','album','photo','note') NOT NULL DEFAULT 'note',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `ip_addr` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `post_id` (`post_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_id` (`parent_id`),
  KEY `album_id` (`album_id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `posts` */

DROP TABLE IF EXISTS `posts`;

CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL DEFAULT '0',
  `cat_id` int(10) unsigned NOT NULL DEFAULT '0',
  `title` char(60) NOT NULL DEFAULT '',
  `type` enum('public','private','draft','note') NOT NULL DEFAULT 'public',
  `comments` smallint(4) unsigned NOT NULL DEFAULT '0',
  `posttags` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `attached` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `recommend` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `date` int(10) unsigned NOT NULL DEFAULT '0',
  `date_lastedit` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `cat_id` (`cat_id`),
  KEY `recommend` (`recommend`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `posts_categories` */

DROP TABLE IF EXISTS `posts_categories`;

CREATE TABLE `posts_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(10) unsigned NOT NULL DEFAULT '0',
  `name` char(30) NOT NULL DEFAULT '',
  `path` char(200) NOT NULL DEFAULT '',
  `level` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `num_posts` int(10) unsigned NOT NULL DEFAULT '0',
  `is_feed` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `posts_text` */

DROP TABLE IF EXISTS `posts_text`;

CREATE TABLE `posts_text` (
  `post_id` int(10) unsigned NOT NULL DEFAULT '0',
  `text` text,
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `relation_posts_tags` */

DROP TABLE IF EXISTS `relation_posts_tags`;

CREATE TABLE `relation_posts_tags` (
  `post_id` int(10) unsigned NOT NULL,
  `tag_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `setting` */

DROP TABLE IF EXISTS `setting`;

CREATE TABLE `setting` (
  `name` varchar(30) NOT NULL DEFAULT '',
  `value` text,
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `tags_posts` */

DROP TABLE IF EXISTS `tags_posts`;

CREATE TABLE `tags_posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(20) NOT NULL DEFAULT '',
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(200) NOT NULL DEFAULT '',
  `fullname` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `about_me` text,
  `gender` enum('','m','f') NOT NULL DEFAULT '',
  `num_posts` int(10) unsigned NOT NULL DEFAULT '0',
  `used_storage` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_date` int(10) unsigned NOT NULL DEFAULT '0',
  `reg_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `lastlogin_date` int(10) unsigned NOT NULL DEFAULT '0',
  `lastlogin_ip` int(10) unsigned NOT NULL DEFAULT '0',
  `lastpost_date` int(10) unsigned NOT NULL DEFAULT '0',
  `lastemail_date` int(10) unsigned NOT NULL DEFAULT '0',
  `lastclick_date` int(10) unsigned NOT NULL DEFAULT '0',
  `pass_reset_key` varchar(32) NOT NULL DEFAULT '',
  `pass_reset_valid` int(10) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `is_network_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`),
  KEY `active` (`active`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;

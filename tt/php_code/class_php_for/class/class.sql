-- phpMyAdmin SQL Dump
-- version 2.11.6
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2009 年 10 月 15 日 12:35
-- 服务器版本: 5.0.51
-- PHP 版本: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `myde520`
--

-- --------------------------------------------------------

--
-- 表的结构 `class`
--

CREATE TABLE `class` (
  `id` int(10) NOT NULL auto_increment,
  `name` varchar(250) character set gbk default NULL,
  `classid` int(10) default NULL,
  `sort` int(10) default '10',
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=gb2312 AUTO_INCREMENT=11 ;

--
-- 导出表中的数据 `class`
--

INSERT INTO `class` (`id`, `name`, `classid`, `sort`) VALUES
(1, '中国', 0, 1),
(2, '广西', 1, 1),
(3, '桂林', 2, 2),
(4, '广东', 1, 2),
(5, '北京', 1, 3),
(6, '东莞', 4, 10),
(7, '南宁', 2, 10),
(8, '阳朔', 3, 10),
(9, '柳州', 2, 10),
(10, '广州', 4, 10);

-- phpMyAdmin SQL Dump
-- version 2.10.2
-- http://www.phpmyadmin.net
-- 
-- 主机: localhost
-- 生成日期: 2008 年 08 月 19 日 15:44
-- 服务器版本: 5.0.41
-- PHP 版本: 5.2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- 数据库: `demo`
-- 

-- --------------------------------------------------------

-- 
-- 表的结构 `demo_user`
-- 

CREATE TABLE `demo_user` (
  `id` int(11) NOT NULL auto_increment COMMENT '自增ID',
  `username` varchar(20) collate utf8_unicode_ci NOT NULL default '' COMMENT '用户名',
  `passwd` varchar(20) collate utf8_unicode_ci NOT NULL default '' COMMENT '登陆密码',
  `email` varchar(60) collate utf8_unicode_ci NOT NULL default '' COMMENT '用户邮箱',
  `regtime` datetime NOT NULL default '0000-00-00 00:00:00' COMMENT '注册时间',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `username_2` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='框架测试表' AUTO_INCREMENT=25 ;

-- 
-- 导出表中的数据 `demo_user`
-- 

INSERT INTO `demo_user` (`id`, `username`, `passwd`, `email`, `regtime`) VALUES 
(1, 'indraw', '111111', 'indraw@msn.com', '2007-03-09 02:17:41'),
(3, 'admin', '111111', 'admin@admin.com', '2007-03-30 00:40:02'),
(4, 'wangyz', 'wangyz123', 'wangyz@dns.com.cn', '2007-03-30 00:40:23'),
(5, 'wyz', '111111', 'wyz@msn.com', '2007-03-30 00:40:40'),
(6, 'iok', '111111', 'indraw@msn.com', '2007-03-30 00:40:56'),
(7, 'slang', '111111', 'slang@msn.com', '2007-03-30 00:41:19'),
(8, 'heaviside', '111111', 'heaviside@msn.com', '2007-03-30 01:05:48'),
(9, '340860', '111111', '340860@qq.com', '2007-04-10 07:27:31'),
(11, 'indraw1', '111111', 'indraw1@163.com', '0000-00-00 00:00:00'),
(12, 'a11', '111111', 'a1@1cm.mobi', '0000-00-00 00:00:00'),
(13, '1182303660', '111111', '1182303660@cernet.com', '2000-01-01 11:01:01'),
(14, '1182303685', '111111', '1182303685@cernet.com', '2000-01-01 11:01:01'),
(15, '1182303764', '111111', '1182303764@cernet.com', '2000-01-01 11:01:01'),
(16, '1182303798', '111111', '1182303798@cernet.com', '2000-01-01 11:01:01'),
(17, '1182303846', '111111', '1182303846@cernet.com', '2000-01-01 11:01:01'),
(18, '1182303860', '111111', '1182303860@cernet.com', '2000-01-01 11:01:01'),
(19, '1182303883', '111111', '1182303883@cernet.com', '2000-01-01 11:01:01'),
(20, '1182303884', '111111', '1182303884@cernet.com', '2000-01-01 11:01:01'),
(21, '1182303922', '111111', '1182303922@cernet.com', '2000-01-01 11:01:01'),
(22, 'aaaaaa', 'aaaaaa', 'aaa@163.com', '0000-00-00 00:00:00'),
(23, 'abc1', '111111', 'abc@1cm.mobi', '0000-00-00 00:00:00'),
(24, 'abcd1', '111111', 'a1@cernet.com', '0000-00-00 00:00:00');

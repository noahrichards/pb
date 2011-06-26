-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: May 09, 2005 at 04:33 PM
-- Server version: 4.1.8
-- PHP Version: 4.3.10
-- 
-- Database: `peanutbutter`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `pb_blog`
-- 

CREATE TABLE `pb_blog` (
  `id` int(11) NOT NULL auto_increment,
  `projid` int(11) NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `user` varchar(80) NOT NULL default '',
  `title` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pb_projects`
-- 

CREATE TABLE `pb_projects` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL default '',
  `owner` varchar(80) NOT NULL default '',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  `description` text NOT NULL,
  `keywords` text NOT NULL,
  `status` enum('In Progress','Completed','On Hold','Cancelled','Pending (see note)') NOT NULL default 'In Progress',
  `progress` float NOT NULL default '0',
  `priority` int(2) NOT NULL default '5',
  `deadline` date default NULL,
  `notes` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `unique` (`name`),
  FULLTEXT KEY `keywords` (`keywords`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii AUTO_INCREMENT=30 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pb_searches`
-- 

CREATE TABLE `pb_searches` (
  `name` varchar(80) NOT NULL default '',
  `owner` varchar(80) NOT NULL default '',
  `terms` text NOT NULL,
  `lastused` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`name`,`owner`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii;

-- --------------------------------------------------------

-- 
-- Table structure for table `pb_tasks`
-- 

CREATE TABLE `pb_tasks` (
  `id` int(11) NOT NULL auto_increment,
  `projid` int(11) NOT NULL default '0',
  `started` datetime NOT NULL default '0000-00-00 00:00:00',
  `finished` datetime NOT NULL default '0000-00-00 00:00:00',
  `user` varchar(80) NOT NULL default '',
  `title` varchar(80) NOT NULL default '',
  `description` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

-- 
-- Table structure for table `pb_users`
-- 

CREATE TABLE `pb_users` (
  `name` varchar(80) character set ascii collate ascii_bin NOT NULL default '',
  `password` varchar(80) character set ascii collate ascii_bin NOT NULL default '',
  `category` enum('siteadmin','admin','normal') NOT NULL default 'normal',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=ascii;

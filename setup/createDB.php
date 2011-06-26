<?php

include_once('../includes/globals.inc');

$saUser = 'noah';
$saPass = 'password';


mysql_connect($dbhost,'dakeyras','password');
mysql_select_db($dbname);

print("Creating pb_blog...");

$query = "CREATE TABLE `pb_blog` ("
	.	"`id` int(11) NOT NULL auto_increment,"
	.	"`projid` int(11) NOT NULL default '0',"
	.	"`added` datetime NOT NULL default '0000-00-00 00:00:00',"
	.	"`modified` datetime NOT NULL default '0000-00-00 00:00:00',"
	.	"`user` varchar(80) NOT NULL default '',"
	.	"`title` varchar(255) NOT NULL default '',"
	.	"`text` text NOT NULL,"
	.	"PRIMARY KEY  (`id`)"
	.	") ENGINE=MyISAM;";

$result = mysql_query($query) or print(mysql_error());

print("done.\n<br /><br />");


print("Creating pb_projects...");

$query = "CREATE TABLE `pb_projects` ("
	.	"`id` int(11) NOT NULL auto_increment,"
	.	"`name` varchar(255) NOT NULL default '',"
	.	"`owner` varchar(80) NOT NULL default '',"
	.	"`added` datetime NOT NULL default '0000-00-00 00:00:00',"
	.	"`modified` datetime NOT NULL default '0000-00-00 00:00:00',"
	.	"`description` text NOT NULL,"
	.	"`keywords` text NOT NULL,"
	.	"`status` enum('In Progress','Completed','On Hold','Cancelled','Pending (see note)') NOT NULL default 'In Progress',"
	.	"`progress` float NOT NULL default '0',"
	.	"`priority` int(2) NOT NULL default '5',"
	.	"`deadline` date default NULL,"
	.	"`notes` text NOT NULL,"
	.	"PRIMARY KEY  (`id`),"
	.	"UNIQUE KEY `unique` (`name`),"
	.	"FULLTEXT KEY `keywords` (`keywords`)"
	.	") ENGINE=MyISAM;";

$result = mysql_query($query) or print(mysql_error());

print("done.\n<br /><br />");


print("Creating pb_searches...");

$query = "CREATE TABLE `pb_searches` ("
	.	"`name` varchar(80) NOT NULL default '',"
	.	"`owner` varchar(80) NOT NULL default '',"
	.	"`terms` text NOT NULL,"
	.	"`lastused` datetime NOT NULL default '0000-00-00 00:00:00',"
	.	"PRIMARY KEY  (`name`,`owner`)"
	.	") ENGINE=MyISAM;";

$result = mysql_query($query) or print(mysql_error());

print("done.\n<br /><br />");


print("Creating pb_tasks...");

$query = "CREATE TABLE `pb_tasks` ("
	.	"`id` int(11) NOT NULL auto_increment,"
	.	"`projid` int(11) NOT NULL default '0',"
	.	"`started` datetime NOT NULL default '0000-00-00 00:00:00',"
	.	"`finished` datetime NOT NULL default '0000-00-00 00:00:00',"
	.	"`user` varchar(80) NOT NULL default '',"
	.	"`title` varchar(80) NOT NULL default '',"
	.	"`description` text NOT NULL,"
	.	"PRIMARY KEY  (`id`)"
	.	") ENGINE=MyISAM;";

$result = mysql_query($query) or print(mysql_error());

print("done.\n<br /><br />");


print("Creating pb_users...");

$query = "CREATE TABLE `pb_users` ("
	.	"`name` varchar(80) NOT NULL default '',"
	.	"`password` varchar(80) NOT NULL default '',"
	.	"`category` enum('siteadmin','admin','normal') NOT NULL default 'normal',"
	.	"`created` datetime NOT NULL default '0000-00-00 00:00:00',"
	.	"PRIMARY KEY  (`name`)"
	.	") ENGINE=MyISAM;";
$result = mysql_query($query) or print(mysql_error());

print("done.\n<br /><br />");


print("Creating siteadmin...");

$query = "INSERT INTO `pb_users` (`name`,`password`,`category`) VALUES "
	.	"('$saUser','$saPass','siteadmin')";

$result = mysql_query($query) or print(mysql_error());

print("done.\n<br /><br />");






?>

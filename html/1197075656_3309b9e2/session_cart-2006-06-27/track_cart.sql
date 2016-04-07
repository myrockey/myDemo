-- phpMyAdmin SQL Dump
-- version 2.6.1
-- http://www.phpmyadmin.net
-- 
-- Generation Time: Jun 16, 2006 at 02:26 PM
-- Server version: 4.1.11
-- PHP Version: 4.3.4
-- 
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `track_cart`
-- 

CREATE TABLE `track_cart` (
  `id` int(3) NOT NULL auto_increment,
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `product_id` int(3) NOT NULL default '0',
  `price` varchar(255) NOT NULL default '',
  `session_id` varchar(255) NOT NULL default '',
  `product_name` text NOT NULL,
  `vat` varchar(255) NOT NULL default '',
  `user_id` varchar(255) NOT NULL default '0',
  `case` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

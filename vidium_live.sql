-- phpMyAdmin SQL Dump
-- version 2.8.2.4
-- http://www.phpmyadmin.net
-- 
-- Servidor: localhost:3306
-- Tiempo de generación: 17-10-2012 a las 09:31:45
-- Versión del servidor: 5.0.51
-- Versión de PHP: 5.2.6
-- 
-- Base de datos: `vidium_live`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `groups`
-- 

DROP TABLE IF EXISTS `groups`;
CREATE TABLE IF NOT EXISTS `groups` (
  `idgroup` int(11) NOT NULL auto_increment,
  `title` varchar(60) NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY  (`idgroup`)
) TYPE=MyISAM AUTO_INCREMENT=3 AUTO_INCREMENT=3 ;
-- Volcar la base de datos para la tabla `groups`
-- 

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `recorders`
-- 

DROP TABLE IF EXISTS `recorders`;
CREATE TABLE IF NOT EXISTS `recorders` (
  `idrecorder` int(11) NOT NULL auto_increment,
  `alias` varchar(100) default NULL,
  `publicip` varchar(30) NOT NULL,
  `hw_addr` varchar(20) NOT NULL,
  `port` varchar(5) NOT NULL,
  `privateip` varchar(30) NOT NULL,
  `updated` datetime default NULL,
  `created` datetime NOT NULL,
  `flag` tinyint(1) NOT NULL,
  PRIMARY KEY  (`idrecorder`)
) TYPE=MyISAM AUTO_INCREMENT=36 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `user_recorder`
-- 

DROP TABLE IF EXISTS `user_recorder`;
CREATE TABLE IF NOT EXISTS `user_recorder` (
  `id` int(11) NOT NULL auto_increment,
  `user_id` int(11) NOT NULL,
  `recorder_id` int(11) NOT NULL,
  `permit` int(11) default NULL,
  `created` datetime NOT NULL,
  `flag` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=260 AUTO_INCREMENT=260 ;

-- --------------------------------------------------------

-- 
-- Estructura de tabla para la tabla `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `iduser` int(11) NOT NULL auto_increment,
  `email` varchar(255) NOT NULL,
  `password` varchar(50) NOT NULL,
  `group_id` int(11) NOT NULL default '2',
  `created` datetime NOT NULL,
  `flag` tinyint(1) NOT NULL,
  PRIMARY KEY  (`iduser`)
) TYPE=InnoDB AUTO_INCREMENT=98 AUTO_INCREMENT=98 ;

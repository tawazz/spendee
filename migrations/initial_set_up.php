<?php

use HTTP\Migrations\Migration;

class InitialSetUp extends Migration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

    public function up()
    {
         $this->execute("-- phpMyAdmin SQL Dump
         -- version 4.5.2
         -- http://www.phpmyadmin.net
         --
         -- Host: 127.0.0.1
         -- Generation Time: Aug 26, 2016 at 01:42 PM
         -- Server version: 5.7.9
         -- PHP Version: 5.6.16

         SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
         SET time_zone = \"+00:00\";


         /*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
         /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
         /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
         /*!40101 SET NAMES utf8mb4 */;

         --
         -- Database: `tawazzne_spender`
         --
         CREATE DATABASE IF NOT EXISTS `tawazzne_spender` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
         USE `tawazzne_spender`;

         -- --------------------------------------------------------

         --
         -- Table structure for table `budget`
         --

         DROP TABLE IF EXISTS `budget`;
         CREATE TABLE IF NOT EXISTS `budget` (
           `id` int(11) NOT NULL AUTO_INCREMENT,
           `user_id` int(11) NOT NULL,
           `name` varchar(64) NOT NULL,
           `amount` decimal(10,2) NOT NULL,
           `start_date` date NOT NULL,
           PRIMARY KEY (`id`),
           KEY `user_id` (`user_id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `bud_tags`
         --

         DROP TABLE IF EXISTS `bud_tags`;
         CREATE TABLE IF NOT EXISTS `bud_tags` (
           `id` int(11) NOT NULL AUTO_INCREMENT,
           `bud_id` int(11) NOT NULL,
           `tag_id` int(11) NOT NULL,
           PRIMARY KEY (`id`),
           KEY `bud_id` (`bud_id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=346 DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `expenses`
         --

         DROP TABLE IF EXISTS `expenses`;
         CREATE TABLE IF NOT EXISTS `expenses` (
           `user_id` int(8) DEFAULT NULL,
           `name` varchar(60) DEFAULT NULL,
           `cost` decimal(19,2) DEFAULT NULL,
           `date` date DEFAULT NULL,
           `exp_id` int(8) NOT NULL AUTO_INCREMENT,
           PRIMARY KEY (`exp_id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=1732 DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `exp_tags`
         --

         DROP TABLE IF EXISTS `exp_tags`;
         CREATE TABLE IF NOT EXISTS `exp_tags` (
           `id` int(11) NOT NULL AUTO_INCREMENT,
           `exp_id` int(11) NOT NULL,
           `tag_id` int(11) NOT NULL,
           PRIMARY KEY (`id`),
           KEY `exp_id` (`exp_id`),
           KEY `tag_id` (`tag_id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=520 DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `incomes`
         --

         DROP TABLE IF EXISTS `incomes`;
         CREATE TABLE IF NOT EXISTS `incomes` (
           `user_id` int(8) DEFAULT NULL,
           `name` varchar(30) DEFAULT NULL,
           `cost` decimal(19,2) DEFAULT NULL,
           `date` date DEFAULT NULL,
           `inc_id` int(8) NOT NULL AUTO_INCREMENT,
           PRIMARY KEY (`inc_id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `inc_tags`
         --

         DROP TABLE IF EXISTS `inc_tags`;
         CREATE TABLE IF NOT EXISTS `inc_tags` (
           `id` int(11) NOT NULL AUTO_INCREMENT,
           `inc_id` int(11) NOT NULL,
           `tag_id` int(11) NOT NULL,
           PRIMARY KEY (`id`),
           KEY `inc_id` (`inc_id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `pageview`
         --

         DROP TABLE IF EXISTS `pageview`;
         CREATE TABLE IF NOT EXISTS `pageview` (
           `user_id` int(11) NOT NULL,
           `visits` int(11) NOT NULL,
           `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
         ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `session`
         --

         DROP TABLE IF EXISTS `session`;
         CREATE TABLE IF NOT EXISTS `session` (
           `id` int(11) NOT NULL AUTO_INCREMENT,
           `user_id` int(11) NOT NULL,
           `hash` varchar(120) NOT NULL,
           PRIMARY KEY (`id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `tags`
         --

         DROP TABLE IF EXISTS `tags`;
         CREATE TABLE IF NOT EXISTS `tags` (
           `id` int(11) NOT NULL AUTO_INCREMENT,
           `name` varchar(255) NOT NULL DEFAULT '',
           PRIMARY KEY (`id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=latin1;

         -- --------------------------------------------------------

         --
         -- Table structure for table `users`
         --

         DROP TABLE IF EXISTS `users`;
         CREATE TABLE IF NOT EXISTS `users` (
           `user_id` int(8) NOT NULL AUTO_INCREMENT,
           `firstname` char(30) NOT NULL,
           `lastname` char(30) NOT NULL,
           `username` varchar(30) NOT NULL,
           `password` varchar(128) NOT NULL,
           `email` varchar(90) NOT NULL,
           PRIMARY KEY (`user_id`)
         ) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=latin1;

         INSERT INTO `users` (`user_id`, `firstname`, `lastname`, `username`, `password`, `email`) VALUES
         (7, 'Admin', 'Admin', 'admin', '057d6ab44a20179a4bb213682beff1ddb9e60f4413e03f0e0df4d84b7b057459', 'root@example.com'),

         --
         -- Constraints for dumped tables
         --

         --
         -- Constraints for table `bud_tags`
         --
         ALTER TABLE `bud_tags`
           ADD CONSTRAINT `fk_budget` FOREIGN KEY (`bud_id`) REFERENCES `budget` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

         --
         -- Constraints for table `exp_tags`
         --
         ALTER TABLE `exp_tags`
           ADD CONSTRAINT `exp_tags_ibfk_1` FOREIGN KEY (`exp_id`) REFERENCES `expenses` (`exp_id`) ON DELETE CASCADE;

         --
         -- Constraints for table `inc_tags`
         --
         ALTER TABLE `inc_tags`
           ADD CONSTRAINT `update inc tags` FOREIGN KEY (`inc_id`) REFERENCES `incomes` (`inc_id`) ON DELETE CASCADE ON UPDATE CASCADE;

         /*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
         /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
         /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
");
    }
}

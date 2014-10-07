-- phpMyAdmin SQL Dump
-- version 4.1.6
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2014 at 03:22 PM
-- Server version: 5.6.16
-- PHP Version: 5.5.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gamegtx`
--

-- --------------------------------------------------------

--
-- Table structure for table `auth_users`
--

CREATE TABLE IF NOT EXISTS `auth_users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `display_name` varchar(100) NOT NULL,
  `status` varchar(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `auth_users`
--

INSERT INTO `auth_users` (`ID`, `username`, `display_name`, `status`) VALUES
(1, 'ali', 'ali', 'active'),
(2, 'hassan', 'hassan', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

CREATE TABLE IF NOT EXISTS `games` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `game_title` varchar(100) NOT NULL,
  `game_desc` text NOT NULL,
  `game_link` varchar(500) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`ID`, `game_title`, `game_desc`, `game_link`) VALUES
(1, 'Tic Tac Toe', 'A Free game for fun ...', 'games/tictaktoe/');

-- --------------------------------------------------------

--
-- Table structure for table `player`
--

CREATE TABLE IF NOT EXISTS `player` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `turn` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `p2p_data` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=229 ;

--
-- Dumping data for table `player`
--

INSERT INTO `player` (`ID`, `user_id`, `room_id`, `turn`, `score`, `p2p_data`) VALUES
(227, 1, 1, 1, 0, '0-0,2-1,2-1,0-0,2-1,0-0,1-2,1-2,1-2+hassan has Won the Game!'),
(228, 2, 1, 0, 0, '0-0,2-1,2-1,0-0,2-1,0-0,1-2,1-2,1-2+hassan has Won the Game!');

-- --------------------------------------------------------

--
-- Table structure for table `rooms`
--

CREATE TABLE IF NOT EXISTS `rooms` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `room_name` varchar(100) NOT NULL,
  `max_players` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`ID`, `room_name`, `max_players`, `game_id`) VALUES
(1, 'TTT1', 2, 1),
(2, 'TTT2', 2, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

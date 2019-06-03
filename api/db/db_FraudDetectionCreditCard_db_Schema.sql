--
-- Database: `frauddetectioncreditcard_db`
--

CREATE DATABASE IF NOT EXISTS `frauddetectioncreditcard_db`;
USE `frauddetectioncreditcard_db`;


-- ENTITIES

--
-- Struttura della tabella `transaction`
--

CREATE TABLE IF NOT EXISTS `transaction` (
	`Amount` decimal(6,2) ,
	`V1` decimal(6,2) ,
	`V2` decimal(6,2) ,
	
	-- RELAZIONI

	`_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT 

);





--
-- Struttura della tabella `user`
--

CREATE TABLE IF NOT EXISTS `user` (
	`mail` varchar(130) ,
	`name` varchar(130) ,
	`password` varchar(130)  NOT NULL,
	`roles` varchar(130) ,
	`surname` varchar(130) ,
	`username` varchar(130)  NOT NULL,
	
	-- RELAZIONI

	`_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT 

);


-- Security

INSERT INTO `frauddetectioncreditcard_db`.`user` (`username`, `password`, `_id`) VALUES ('admin', '62f264d7ad826f02a8af714c0a54b197935b717656b80461686d450f7b3abde4c553541515de2052b9af70f710f0cd8a1a2d3f4d60aa72608d71a63a9a93c0f5', 1);

CREATE TABLE IF NOT EXISTS `roles` (
	`role` varchar(30) ,
	
	-- RELAZIONI

	`_user` int(11)  NOT NULL REFERENCES user(_id),
	`_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT 

);
INSERT INTO `frauddetectioncreditcard_db`.`roles` (`role`, `_user`, `_id`) VALUES ('ADMIN', '1', 1);







<?php
	include 'config.php';
	include 'class.MySQL.php';
	
	// Establish connection
	$mySQL = new MySQL($DB_DATABASE, $DB_USER, $DB_PASSWORD, $DB_HOST) or die("Can't connect to the DB, verify config.php");
	
	$sql = "CREATE TABLE IF NOT EXISTS `${DB_TABLE_PREFIX}orders` (
	  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	  `amount` double NOT NULL,
	  `pay_to` varchar(38) NOT NULL,
	  `salt_hash` varchar(32) NOT NULL,
	  PRIMARY KEY (`id`)
	)";
	
	$mySQL->executeSQL($sql);
	
	$sql = "CREATE TABLE IF NOT EXISTS `${DB_TABLE_PREFIX}payments` (
	  `input_transaction_hash` varchar(70) NOT NULL,
	  `transaction_hash` varchar(70) DEFAULT NULL,
	  `amount` double NOT NULL,
	  `confirmed` tinyint(1) NOT NULL DEFAULT '0',
	  `order` int(10) unsigned NOT NULL,
	  PRIMARY KEY (`input_transaction_hash`,`order`)
	)";
	
	$mySQL->executeSQL($sql);
	
	// Close connection
	$mySQL->closeConnection();
	
	echo "Done!";
?>
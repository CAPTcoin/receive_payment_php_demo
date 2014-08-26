<?php
	include 'config.php';
	include 'class.MySQL.php';
	
	if( empty($_REQUEST['id']) ) {
		die("No id passed");
	}
	
	// Establish connection
	$mySQL = new MySQL($DB_DATABASE, $DB_USER, $DB_PASSWORD, $DB_HOST);
	
	// Get details of this order
	$order = $mySQL->select("${DB_TABLE_PREFIX}orders", array('id'=>$_REQUEST['id']));
	
	if( ! is_array( $order ) ) {
		die("Order not found");
	}
	
	// Close connection
	$mySQL->closeConnection();
	
	include 'details_view.php';
?>
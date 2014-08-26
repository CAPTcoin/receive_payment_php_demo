<?php
	// Is this a test?
	// The API sends test=true when using:
	//	https://api.captcoin.com/test/
	if( ! empty($_GET['test']) ) {
		// Comment this to test
		die('*ok*');
	}

	include 'config.php';
	include 'class.MySQL.php';
	
	// Check my address
	if( $MY_ADDRESS!=$_GET['destination_address'] ) {
		die("Invalid address");
	}

	// Are we using a secret key?
	if( ! empty($SECRET_KEY) && $SECRET_KEY!=$_GET['secret_key'] ) {
		die("Invalid secret key");
	}
	
	// Get the salt from the DB to verify security hash
	$order_id = $_GET['order'];
	if( !$order_id ) {
		die("No order ID");
	}
	// Establish connection
	$mySQL = new MySQL($DB_DATABASE, $DB_USER, $DB_PASSWORD, $DB_HOST);
	
	$order = $mySQL->select("${DB_TABLE_PREFIX}orders", array('id'=>$order_id));
	
	// Check the security hash
	if( ! verifySecurityHash( $order['salt_hash'] ) ) {
		$mySQL->closeConnection();
		die("Invalid security hash");
	}
	
	// Update or insert the payment
	// $_GET['confirmations'] checks it has enough verifications for the transaction to be trusted
	// $_GET['transaction_hash'] checks that the transaction was already forwarded to my address
	$confirmed = $_GET['confirmations'] >= 4 && $_GET['transaction_hash'];
	
	if( $mySQL->countRows( "${DB_TABLE_PREFIX}payments", array('input_transaction_hash'=>$_GET['input_transaction_hash'], 'order'=>$order_id) ) ) {
		// It already exists, update the number of confirmations, transaction_hash and confirmed
		$mySQL->update( "${DB_TABLE_PREFIX}payments", array('transaction_hash'=>$_GET['transaction_hash'], 'confirmed'=>$confirmed), array('input_transaction_hash'=>$_GET['input_transaction_hash'], 'order'=>$order_id) );
	} else {
		// This tx hasn't been registered yet. Insert it.
		$amount = $_GET['value'] / 100000000; // Convert from CAPToshis to CAPTcoins
		$mySQL->insert( "${DB_TABLE_PREFIX}payments", array('input_transaction_hash'=>$_GET['input_transaction_hash'], 'transaction_hash'=>$_GET['transaction_hash'], 'amount'=>$amount, 'order'=>$order_id, 'confirmed'=>$confirmed) );
	}
	
	if( $confirmed ) {
		// We don't need more notifications from this tx
		echo "*ok*";
	} else {
		// We need more notifications
		echo "Waiting for confirmation";
	}
	
	// Close connection
	$mySQL->closeConnection();
	
	function verifySecurityHash( $salt_hash ) {
		if( ! $salt_hash || ! $_GET['security_hash'] ) {
			return false;
		}
	
		// The hash is calculated from the given parameters
		$my_hash  = md5( $salt_hash . "-" . $_GET['input_transaction_hash'] . "-" . $_GET['value'] . "-" . $_GET['confirmations'] );
		
		return ( $my_hash == $_GET['security_hash'] );
	}
?>
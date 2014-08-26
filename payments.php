<?php
	include 'config.php';
	include 'class.MySQL.php';
	
	if( empty($_REQUEST['id']) ) {
		die("No id passed");
	}
	
	// Establish connection
	$mySQL = new MySQL($DB_DATABASE, $DB_USER, $DB_PASSWORD, $DB_HOST);
	
	$total = 0;
	
	// Get the payments for this order
	$payments = $mySQL->select("${DB_TABLE_PREFIX}payments", array('order'=>$_REQUEST['id']));
	
	if( $mySQL->records == 0 ) {
		echo "<i>No payments yet</i>";
	} else if( $mySQL->records == 1 ) {
		$total += $payments['amount'];
		// Convert to array
		printPayment( $payments );
	} else {
		foreach( $payments as $payment ) {
			$total += $payment['amount'];
			printPayment( $payment );
		}
	}
	
	// Total
echo <<<END
					<br/><br/>
					<b>Total: </b>$total CAPT
END;
	
	// Close connection
	$mySQL->closeConnection();
	
	function printPayment( $payment ) {
		global $EXPLORER;
		$amount = $payment['amount'];
		$confirmed = $payment['confirmed'] ? "Yes" : "No";
		$tx = $payment['input_transaction_hash'];
		$link = $EXPLORER . "tx/" . $tx;
echo <<<END
					<br/>
					<b>Amount: </b> $amount CAPT<br/>
					<b>Confirmed: </b> $confirmed<br/>
					<b>Transaction: </b> <a href="$link" target="_blank">$tx</a><br/>
END;
	}
?>
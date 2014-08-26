<?php
	include 'config.php';
	include 'class.MySQL.php';

	// Was the form submitted?
	if( ! empty($_REQUEST['order']) ) {
		process_form();
		die();
	}
	
	// If prices are not in CAPTcoin already, convert them
	if( 'CAPT'!=$CURRENCY ) {
		// Get the conversion rates from the CAPTcoin API
		$rate_json = json_decode( file_get_contents($API . "ticker/" . $CURRENCY) );
		$rate = $rate_json->$CURRENCY;
		
		foreach( $PRODUCTS as $product=>$price ) {
			$PRODUCTS[$product] = number_format($price / $rate, 4, ".", "");
		}
	}
	
	// Amount options
	$options = "";
	for( $amount = 0; $amount<10; ++$amount ) {
		$options .= "<option value=\"$amount\" >$amount</option>";
	}

	include 'create_order_view.php';
	
	function process_form() {
		global $DB_USER, $DB_PASSWORD, $DB_DATABASE, $DB_HOST, $API, $MY_ADDRESS, $MY_SITE, $DB_TABLE_PREFIX, $SECRET_KEY;
	
		// Calculate the amount to charge (in CAPT)
		$captcoins = 0;
		$products = $_REQUEST['product'];
		$prices = $_REQUEST['price'];
		
		for( $position = 0; $position < count($products); ++$position ) {
			$quantity = $products[$position];
			
			if( $quantity > 0 ) {
				$captcoins = $captcoins + $quantity * $prices[$position];
			}
		}
		
		// Establish connection
		$mySQL = new MySQL($DB_DATABASE, $DB_USER, $DB_PASSWORD, $DB_HOST);
		
		// Create a new order
		$mySQL->insert( "${DB_TABLE_PREFIX}orders", array( "amount"=>$captcoins ) );
		$orderId = $mySQL->lastInsertID();
		
		// Where should the API call to notify a new payment?
		$callback_url = $MY_SITE . "callback.php?order=" . $orderId;
		
		// Are we using a secret key?
		if( ! empty($SECRET_KEY) ) {
			$callback_url .= "&secret_key=" . $SECRET_KEY;
		}
		
		// URL to call the API
		$api_call = $API . "receive?method=create&address=$MY_ADDRESS&callback=" . urlencode($callback_url);
		
		$api_response = json_decode( file_get_contents( $api_call ) );
		
		// Get the receiving address and the security hash
		$address = $api_response->input_address;
		
		// The salt hash is sent encrypted (by SSL) so it's more secure than $SECRET_KEY (see callback.php)
		$salt = $api_response->salt_hash;
		
		// Update the new order with this information
		$mySQL->update( "${DB_TABLE_PREFIX}orders", array("pay_to"=>$address, "salt_hash"=>$salt), array("id"=>$orderId) );
		
		// Close connection
		$mySQL->closeConnection();
		
		// Forward to order details
		header("Location: details.php?id=" . $orderId);
	}
?>
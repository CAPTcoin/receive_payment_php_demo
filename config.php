<?php
	// MySQL Database: Change this information
	$DB_USER = 'demo';
	$DB_PASSWORD = '<mysql_password>';
	$DB_DATABASE= 'demo';
	$DB_HOST = 'localhost';

	// Only change if required, useful to install on a shared DB
	$DB_TABLE_PREFIX = 'capt_';
	
	// My site: The API will call it. It needs to be accessible by it (don't use localhost).
	$MY_SITE = "http://example.com/receive_payment_php_demo/";
	
	// My CAPT address: Payments will be forwarded here
	$MY_ADDRESS = 'Ce4c3qSQFbWrhSrGCKMwvRb6GHmD2wzvua'; // Change this!
	
	// A unique string, without spaces or special characters
	$SECRET_KEY = "xqtgO1CkH75oYu42Xpw3"; // Optional
	
	// Currency
	// Supported currencies: CAPT (CAPTcoins), BTC (Bitcoin), AUD, BRL, CAD, CHF, CNY, EUR, GBP, HKD,
	//							IDR, ILS, MXN, NOK, NZD, PLN, RON, RUB, SEK, SGD, TRY, USD, and ZAR.
	$CURRENCY = "USD";
	
	// Demo Products
	// Name and price in $CURRENCY
	$PRODUCTS = array( "Chocolate"=>0.99,
						"Water"=>1.49,
						"Bread"=>2.99 );

	// API root
	$API = "https://api.captcoin.com/";
	// Block explorer
	$EXPLORER = "http://explore.captcoin.com/";
?>
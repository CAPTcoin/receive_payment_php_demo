# What is this?
A basic PHP/MySQL example on how to implement CAPTcoin's API to easily process CAPTcoin payments.

It uses these APIs:
- Receive Payments API: https://api.captcoin.com/
- Ticker API: https://api.captcoin.com/ticker/

It converts all the prices to CAPTcoins (using Ticker API) and lets the user create an order.
Then it automatically updates the order with the received payments (using Receive Payments API).

Do not use in production as is!

# Instructions
	* Clone the git repository into your web server.
	* cd receive_payment_php_demo
	* Edit config.php. Update at least $DB_*, $MY_SITE and $MY_ADDRESS
	* Run install.php to create the required DB tables
	* Open the demo: http://example.com/receive_payment_php_demo/index.php

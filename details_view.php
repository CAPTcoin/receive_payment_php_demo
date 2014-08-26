<!DOCTYPE HTML>
<html>
<head>
	<meta charset="UTF-8">
	<title>CAPTcoin - Receive Payments API - Demo</title>
	<link rel="stylesheet" href="css/style.css" type="text/css">
	<script src="js/jquery-1.11.0.min.js"></script>
	<link rel="shortcut icon" href="images/capt.ico"/>
</head>
<body>
	<div id="header">
		<div>
			<div class="logo">
				<a href="http://CAPTcoin.com/">CAPTcoin</a>
			</div>
			<ul id="navigation">
				<li>
					<a href="http://CAPTcoin.com/">CAPTcoin</a>
				</li>
				<li>
					<a href="https://api.CAPTcoin.com/">CAPTcoin API</a>
				</li>
			</ul>
		</div>
	</div>
	<div id="getwallet" style="height: 500px;">
		<div class="clearfix">
			<div>
				<h2>Order payment</h2>
				<p>
					<br/>
					Please send <b><?php echo $order['amount']; ?> CAPT</b> to the address <b><?php echo $order['pay_to']; ?></b>.
					<br/>
				</p>
				<p>
					<br/><br/>
				</p>
				<h2>Current payments</h2>
				<p id="payments"></p>
			</div>
		</div>
	</div>
	
	<script>
		function update()
		{
			$.get(
					"payments.php",
					{
						id: <?php echo $_REQUEST['id']; ?> 
					},
					function(data){
						$('#payments').html(data);						
						setTimeout(update, 2500);
					},
					"html"
			);
		}
		update();
	</script>
</body>
</html>
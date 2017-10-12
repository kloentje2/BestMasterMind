<?php
require "config.php";
require 'Mollie/API/Autoloader.php';

$mollie = new Mollie_API_Client;
$mollie->setApiKey($molliekey['live']);

$q = $con->query("SELECT id,mollie_id FROM pro_orders WHERE status = 'unpaid' ORDER BY id DESC LIMIT 1");
$row = $q->fetch_assoc();

$payment_id = $row['mollie_id'];
$payment    = $mollie->payments->get($payment_id);

/*
 * The order ID saved in the payment can be used to load the order and update it's
 * status
 */
$order_id = $payment->metadata->order_id;

if ($payment->isPaid())
{
    $userupdate = $con->query("UPDATE users SET role='pro' WHERE id = '".$con->real_escape_string($_SESSION['uid'])."'");
    $orderupdate = $con->query("UPDATE pro_orders SET status='paid' WHERE id='".$row['id']."'");
	
	if ($userupdate) {
		if ($orderupdate) {
			//header( "Refresh:5; url=exit.php", true, 303);
			Header("Location:exit.php");
			echo "<script>alert('De betaling is geslaagd en je account is geactiveerd! Log opnieuw in.');</script>";
			exit;
		} else {
			echo "Er heeft zich een technische fout voorgedaan, contacteer de beheerder als dit vaker gebeurd. <br>(Foutcode: #23)";
			exit;
		}
	} else {
		echo "Er heeft zich een technische fout voorgedaan, contacteer de beheerder als dit vaker gebeurd. <br>(Foutcode: #22)";
		exit;
	}
}
elseif (! $payment->isOpen())
{
	header( "Refresh:5; url=account.php", true, 303);
	header("Location:account.php");
    echo "<script>alert('De betaling is helaas mislukt. Probeer het nogmaals.');</script>";
	exit;
}
echo "<script>alert('De betaling is helaas mislukt of nog niet afgerond. Probeer het nogmaals.');</script>";
Header("Location:account.php");
?>
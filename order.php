<?php
require "config.php";
require 'Mollie/API/Autoloader.php';

$mollie = new Mollie_API_Client;
$mollie->setApiKey($molliekey['live']);
$hash = md5($_SERVER['REMOTE_ADDR']."-".date("d m y h i s u"));
try
{
    $payment = $mollie->payments->create(
        array(
            'amount'      => 5.00,
            'description' => 'MasterMind Pro',
            'redirectUrl' => 'https://koenhollander.nl/mastermind/checkorder.php',
            'webhookUrl'  => 'https://koenhollander.nl/mastermind/webhook.php',
            'metadata'    => array(
                'order_id' => $hash
            )
        )
    );

    /*
     * Send the customer off to complete the payment.
     */
	$con->query("DELETE FROM pro_orders WHERE uid = '".$con->real_escape_string($_SESSION['uid'])."' AND status='unpaid'");
	$con->query("INSERT INTO pro_orders (uid,price,mollie_id) VALUES ('".$con->real_escape_string($_SESSION['uid'])."','5.00','".$payment->id."')");
    header("Location: " . $payment->getPaymentUrl());
    exit;
}
catch (Mollie_API_Exception $e)
{
    echo "API call failed: " . htmlspecialchars($e->getMessage());
    echo " on field " . htmlspecialchars($e->getField());
}
?>
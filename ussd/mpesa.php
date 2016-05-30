<?php
session_start();

require('../config/AfricasTalkingGateway.php');
require('../config/connect.php');
$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];



$api_key = "3e27348eec7383052689d610ed503f6a6a93671526a88bc1aa7b88e07226b89b";
$username = "njerucyrus";
$phoneNumber="+254705030772";
 
     $currency = "KES";
     $productName = "Payment_njerucyrus";
     $metadata =array('id'=>"888888");
      $gateway = new AfricasTalkingGateway($username,$api_key);
 $transaction_id = $gateway->promptMobilePaymentCheckout($productName, $phoneNumber, $currency, $amount,$metadata);



?>
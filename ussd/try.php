<?php
session_start();

require('../config/AfricasTalkingGateway.php');
require('../config/connect.php');


$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];

// //post variables for the payment
// $transactionId =$_POST["transactionId"];
// $status = $_POST["status"];
// $amt = $_POST["amount"];

$api_key = "3e27348eec7383052689d610ed503f6a6a93671526a88bc1aa7b88e07226b89b";
$username = "njerucyrus";

$incomming= explode('*', $text );

$incomming_text = $incomming[1];

if ( $text == "" ) {
     $response  ="CON Welcome To HELB-LOAN Repayment Gateway,\nReply With:\n";
     $response .="1. Repay My Loan\n";
     $response .="2. Check Loan Status\n";
     $response .="3. My Repayment Plan";
}
if($text == "1"){
     $response .= "CON Enter The amount you want to Repay.";
     
}

if (!empty($incomming_text)||$text!=null){
     $response = "END Request Sent. Please Complete the Payment through the prompt sent";
     $amount = 200;//(int)$incomming_text;
     $currency = "KES";
     $productName = "Payment_njerucyrus";
     $metadata =array('id'=>"888888");
              
     $phone =(string)$phoneNumber;
     
     if (is_numeric($amount)){
          
          try{
                 $gateway = new AfricasTalkingGateway($username,$api_key);
                 $transaction_id = $gateway->promptMobilePaymentCheckout($productName, $phoneNumber, $currency, $amount,$metadata,$username,$api_key);
           
          
          //record transaction in the database
         $sql = "INSERT INTO repayment_log (transaction_id,phoneNumber,amount)VALUES('{$transaction_id}',
         '{$phoneNumber}','{$amt}') ";
         
              $query = $conn->query($sql);
               $response = "END Request Sent. Please Complete the Payment through the prompt sent";
              //check if the query was successful.
              if($query){
                   $sql_update = "UPDATE loan_account SET amount_due =amount_due -'{$amt}', amount_paid = amount_paid+'{$amt}'
                   WHERE phone_no ='{$phoneNumber}'";
                   
              }
               
          }
          catch(AfricasTalkingGatewayException $e){
               $logfile = fopen("log.txt", "w") or die("Unable to open file!");
               $error = $e->getMessage();
               fwrite($logfile, $error);
               fwrite($logfile, $error);
               fclose($logfile);
          
          }
     }
     else{
          $response = "END invalid value for amount. Amount should be a number.";
     }
}
if($text == "2"){
     //check loan payment status 
     $sql = "SELECT * FROM loan_account WHERE phone_no = '{$phoneNumber}'";
     $query = $conn->query($sql);
     if($query->num_rows > 0){
          
               $row=$query->fetch_assoc();
               $full_name = $row['full_name'];
               $unpaid = $row['amount_due'];
               $id =$row['national_id'];
               
               $unpaid_int = (int)$unpaid;
               if($unpaid_int == 0){
                    $response ="END Dear ".$full_name. " ID NO ".$id. " Your Helb-loan Account Is cleared. You have KSH 0.00 Unpaid\nThankyou for clearing your loan";
               }
               elseif($unpaid_int >0){
                    $response = "END Dear ".$full_name. "ID NO".$id." You have  Ksh ".$unpaid."00 unpaid. Please Repay your Loan Via Mpesa By dialing *384*1700# . Thankyou\n--HELB";
     }
     }
     else{
          $response = "END Sorry We could not find your details Please Visit HELB Offices for more info";
     }
}

if($text == "3"){
     //Check my loan repayment plan.
     $sql = "SELECT * FROM repayment_plan WHERE phone_no= '{$phoneNumber}'";
     $query = $conn->query($sql);
     if($query->num_rows > 0){
          
          // create an associative array of query 
          $row = $query->fetch_assoc();
          $full_name = $row['full_name'];
          $id = $row['national_id'];
          $repayment_plan = $row['repayment_mode'];
          $plan_amount = $row['repayment_amount'];
          
          $response = "END Dear ".$full_name." ID NO: ".$id." Your Loan Repayment  is on ".$repayment_plan. " Basis @ Ksh ".$plan_amount.".00\n --Thankyou HELB Team";
          
     }
}

$conn ->close();
header('Content-type: text/plain');
echo $response;

?>

<?php
require('../config/AfricasTalkingGateway.php');
require('../config/connect.php');


$sessionId   = $_POST["sessionId"];
$serviceCode = $_POST["serviceCode"];
$phoneNumber = $_POST["phoneNumber"];
$text        = $_POST["text"];


$paymentProduct="Payment_njerucyrus";

$appData=array('id' =>"288888", 'name'=>"HELB-Loan");


$incomming= explode('*', $text );

$incomming_text = $incomming[1];

if ( $text == "" ) {
     $response  ="CON Welcome To HELB-LOAN Repayment Gateway,\nReply With:\n";
     $response .="1. Repay My Loan\n";
     $response .="2. Check Loan Status\n";
     $response .="3. My Repayment Plan\n";
     $response .="4. Create Repayment Plan.";
}
if($text == "1"){
     
     $response .= "CON Enter The amount you want to Repay.";
     
}

if (!empty($incomming_text)){
    
    //check if the user is an helb beneficiary
    $get_user = "SELECT * FROM loan_account WHERE phone_no='{$phoneNumber}'";
    $query = $conn->query($get_user);
    if($query->num_rows == 1){
         
        $api_key = "3e27348eec7383052689d610ed503f6a6a93671526a88bc1aa7b88e07226b89b";
        $username = "njerucyrus";
        $phone =$_POST['phoneNumber'];
        $phoneNumber ="+254705822035";//(string)$phone;
        $amount =(int)$incomming_text;
        $currency = "KES";
        $productName = "Payment_njerucyrus";
        $metadata =array('id'=>"888888");
        try{
        $gateway = new AfricasTalkingGateway($username,$api_key);
        
        $transaction_id = $gateway->promptMobilePaymentCheckout($productName, $phoneNumber, $currency, $amount,$metadata);
         $response = "END You request has been successfully been submitted. Please Complete the Transaction with the next prompt ";
        }
        catch(AfricasTalkingGatewayException $e){
             
        }
    }
    else if($query->num_rows <=0){
        $response = "END Your Loan Information Couldn't be found.Visit HELB Offices for more info.";
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
                    $response = "END Dear ".$full_name. "ID NO".$id." You have  Ksh ".$unpaid.".00 unpaid. Please Repay your Loan Via Mpesa By dialing *384*1700# . Thankyou\n--HELB";
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
     else{
         $response ="END Sorry you are currently not Enrolled to any Loan Repayment Plan please dial *384*1700*4# To enroll";
     }
}

if ($text == "4"){
     $response  = "CON Please Choose Your Repayment Plan\nReply With:\n";
     $response .= "1. Daily Plan\n";
     $response .= "2. Weekly Plan\n";
     $response .= "3. Monthly Plan";
}
$plan = $incomming[1];
if(!empty($plan)){
     if($text == "4*1"){
     $response = "CON Enter amount you wish to be repaying Daily";
          
     }
     $daily_amount = $incomming[2];
     if(!empty($daily_amount)){
          $myplan = "Daily";
          $amt =(int)$daily_amount;
          
          $sql = "update repayment_plan set repayment_mode ='{$myplan}', repayment_amount='{$amt}'
          where phone_no ='{$phoneNumber}'";
          $update_query =$conn->query($sql);
          if($update_query){
             $response = "END You have Successfully Set Your HELB Loan Repayment Plan of KSH ".$daily_amount.".00 ".$myplan;  
          }
           else{
                $response = "END An Error Occured Please Try again Latter.";
           }
          
          
     }
     
     else if($text =="4*2"){
         $response = "CON Enter amount you wish to be repaying Weekly";
     }
      $weekly_amount = $incomming[2];
     if(!empty($weekly_amount)){
          $myplan = "Weekly";
          $amt =(int)$weekly_amount;
          
          $sql = "update repayment_plan set repayment_mode ='{$myplan}', repayment_amount='{$amt}'
          where phone_no ='{$phoneNumber}'";
          $update_query =$conn->query($sql);
          if($update_query){
             $response = "END You have Successfully Set Your HELB Loan Repayment Plan of KSH ".$weekly_amount.".00 ".$myplan;  
          }
           else{
                $response = "END An Error Occured Please Try again Latter.";
           }
          
          
     }
     else if($text=="4*3"){
           $response = "CON Enter amount you wish to be repaying Monthly";
     }
     
     $monthly_amount = $incomming[2];
     if(!empty($monthly_amount)){
          $myplan = "Monthly";
          $amt =(int)$monthly_amount;
          $sql = "update repayment_plan set repayment_mode ='{$myplan}', repayment_amount='{$amt}'
          where phone_no ='{$phoneNumber}'";
          $update_query =$conn->query($sql);
          if($update_query){
             $response = "END You have Successfully Set Your HELB Loan Repayment Plan of KSH ".$monthly_amount.".00 ".$myplan;  
          }
           else{
                $response = "END An Error Occured Please Try again Latter.";
           }
          
          
     }
     

}



$conn ->close();
header('Content-type: text/plain');
echo $response;

?>

<?php
require('../config/AfricasTalkingGateway.php');
require('../config/connect.php');
// get post via AfricasTalkingGateway API

$from = $_POST['from'];
$to = $_POST['to'];
$text = $_POST['text'];
$date = $_POST['date'];
$id = $_POST['id'];
$linkId = $_POST['linkId'];

//credentials login
$api_key = "3e27348eec7383052689d610ed503f6a6a93671526a88bc1aa7b88e07226b89b";
$username = "njerucyrus";
if (isset($from)){
    
    if(trim($text) == "reg"){
    $sql = "SELECT * FROM message_table WHERE phoneNumber = '{$from}' ORDER BY date_updated asc LIMIT 1";
    $query = $conn->query($sql);
    
    if($query->num_rows >0){
        
    }
    
    try{
        $recipients=$from;
        $gateway = new AfricasTalkingGateway($username, $api_key);
        $gateway->sendMessage($recipients,$message,"20880"); 
        
        
    }
    catch(AfricasTalkingGatewayException $e){
        
        echo $e->getMessage();
    }
    
}



    
}





?>
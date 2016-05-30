<?php
require('../config/connect.php');
?>

<html>
<head>
	<meta charset ="utf-8">
	<title>HELB REPAYMENT</title>
	<meta name="viewport" content="width=device=width, initial scale=1">
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
	<script src="bootstrap/js/jquery-1.12.3.min.js"></script>
<script src="bootstrap/js/bootstrap.min.js"></script>
<style type="text/css"></style>
</head>
<body>
<div class="container-fluid">
	<center>
<h1>Helb Repayment Admin </h1>

<form class="form-horizontal" role="form" method="POST">
<div class="form-group">
<label for="surname" class="col-sm-4 control-label">Surname<label style="color:red;">*</label></label>
<div class="col-sm-6">
<input type="text" class="form-control" name="surname"
placeholder="Enter Surname">
</div>
</div>
<div class="form-group">
<label for="middlename" class="col-sm-4 control-label">MiddleName <label style="color:red;">*</label></label>
<div class="col-sm-6">
<input type="text" class="form-control" name="MiddleName"
placeholder="Enter Middle Name">
</div>
</div>

<div class="form-group">
<label for="lastname" class="col-sm-4 control-label">OtherName</label>
<div class="col-sm-6">
<input type="text" class="form-control" name="OtherName"
placeholder="Enter Other Name">
</div>
</div>

<div class="form-group">
<label for="national_Id" class="col-sm-4 control-label">National ID <label style="color:red;">*</label></label>
<div class="col-sm-6">
<input type="text" class="form-control" name="national_id"
placeholder="Enter the National ID number">
</div>
</div>

<div class="form-group">
<label for="phone number" class="col-sm-4 control-label">Phone Number <label style="color:red;">*</label></label>
<div class="col-sm-6">
<input type="text" class="form-control" name="phone_no"
placeholder="Enter the Phone Number">
</div>
</div>

<div class="form-group">
<label for="amount paid" class="col-sm-4 control-label">Amount Paid <label style="color:red;">*</label></label>
<div class="col-sm-6">
<input type="text" class="form-control" name="Amount_Paid"
placeholder="Enter Amount to be paid">
</div>
</div>

<div class="form-group">
<div class="col-sm-offset-2 col-sm-10">
<button type="submit" class="btn btn-default" name="save">Save</button>
</div>
</div>



</form>

	</center>

</div>
</body>
</html>

<?php
//capturing data
$Surname=$_POST['surname'];
$MiddleName=$_POST['MiddleName'];
$OtherName=$_POST['OtherName'];
$phone_no=$_POST['phone_no'];
$ID=$_POST['national_id'];
$Amount=$_POST['Amount_Paid'];

if(isset($_POST['save']))
{

//checking if person exists
	$sqlcheck="SELECT * FROM `loan_account` WHERE `national_id`='$ID'&&`phone_no`='$phone_no'";
	$result=mysqli_query($conn,$sqlcheck);
	$rowcount=mysqli_num_rows($result);

  if(!$rowcount>0)
  {
	$fullname=($Surname." ".$MiddleName." ".$OtherName);
$sql="INSERT INTO `loan_account`(`id`, `national_id`, `full_name`, `phone_no`, `amount_due`, `amount_paid`, `status`) VALUES ('','$ID','$fullname','$phone_no','$Amount','','')";
$sql1="INSERT INTO `repayment_plan`(`id`, `national_id`, `full_name`, `phone_no`, `repayment_mode`, `repayment_amount`) VALUES ('','$ID','$fullname','$phone_no','','')";
$conn->query($sql);
$conn->query($sql1);
echo "<script type='text/javascript'>
 alert('You have successfully registered $fullname')
 </script>
 ";
  }
 else
 {
 echo "<script type='text/javascript'>
 alert('You already exist in the System')
 </script>
 ";
 
 }

mysqli_close($conn);
}

?>
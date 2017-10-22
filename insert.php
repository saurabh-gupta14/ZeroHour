<?php
 require_once('connect.php');
 if (!isset($_COOKIE['hospitalid']))
	{ // If cookie is not set redirect to login page
		header("Location: login.php");
	}
if(isset($_POST['blood_type']) && isset($_POST['expiry']) && isset($_GET['blood_uid']) ){
 
 	$hid= $_COOKIE['hospitalid']; // Extracting value of hospital id from set cookie
 	$blood_type = htmlentities($_POST['blood_type']);
 	$expiry= htmlentities($_POST['expiry'] );
 	$blood_uid= htmlentities($_GET['blood_uid'] );

 if (!empty($blood_type) && !empty($expiry) && !empty($blood_uid)) {

    $result = mysqli_query($connection,"SELECT * FROM `hospital_record` WHERE hr_uid='$blood_uid' and h_id='$hid'");
     print_r($result);
    if( empty($result) ){
 		$hospital_id = $hid;       // Using value hospital id from set cookie
 		$date_added= Date("Y-m-d",time());
 		$query = "INSERT INTO `hospital_record` (hr_uid,hr_bloodtype,hr_dateadded,hr_expiry, h_id) VALUES ('$blood_uid','$blood_type','$date_added','$expiry', '$hospital_id')";//Added hr_expiry in schema
 		$result = mysqli_query($connection, $query);
 		$expiry_result = mysqli_query($connection,"SELECT MIN(hr_expiry) expiry FROM `hospital_record` WHERE hr_bloodtype='$blood_type' and h_id='$hospital_id'");
 		$fetch= mysqli_fetch_assoc($expiry_result);
 		$expiry= $fetch['expiry'];
 		if($result){
 			echo "Data Updated Successfully!";
 			$result = mysqli_query($connection, "UPDATE `hospital_database` SET hd_bloodunits = hd_bloodunits+1, hd_expiry='$expiry' WHERE `hospital_database`.`h_id` = '$hid' AND `hospital_database`.`hd_bloodtype`='$blood_type'" );
 		}else{
 			echo "Data Not Updated";
 		}
 	}else{
         echo "<script>alert('Data already exists!')</script>";   
    }
 }
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Hospital Record</title>
	<style>
		table{
			border: 6px solid black;
		}
		table th{
			border: 1px solid black;
		}
		table td{
			border: 1px solid black;
		}
		label{
			
			color:black;
		}
		body{
			
			background-color: lightgray;
		}
		
		</style>
		 <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>LIFE-LINE Login Form</title>

        <!-- CSS -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" href="assets/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="assets/css/form-elements.css">
        <link rel="stylesheet" href="assets/css/style.css">
		<link href="css/agency.min.css" rel="stylesheet">
		<link href="css/modern-business.css" rel="stylesheet">
		<link href="css/agency.css" rel="stylesheet">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->

        <!-- Favicon and touch icons -->
        <link rel="shortcut icon" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/11.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/11.png">
</head>
<body>
<div>
<?php require_once 'navbar3.php'; ?>
<br><br><br>
<center>
 	<form method="POST">
 	<label for="blood_type">Blood Type:</label>
 	 <select   name="blood_type" id="btype" >
    
                      <option value="A+">A+</option>
                      <option value="A-">A-</option>
                      <option value="B+">B+</option>
                      <option value="B-">B-</option>
                       <option value="AB+">AB+</option>
                      <option value="AB-">AB-</option>
                      <option value="O+">O+</option>
                      <option value="O-">O-</option>
                    </select> 
                    <br><br>
 	<!--
	Changed hospital id to expiry date as hospital id is automatically retrieved through cookies
 -->
 	<label for="expiry">Expiry Date:</label>	
	
 	<input type="Date" name="expiry" id="expiry" value=<?php echo date("Y-m-d",strtotime("+ 6 weeks") ); ?>><br/><br>
 	<input type="submit" value="Confirm">
    </form>
    <br>
    <h2><font color="black">Update History:</font></h2>
    <table width=80% height=80% >
			<tr>
				<th >Unique id</th>
				<th >Blood Group</th>
				<th >Date Added</th>
				<th >Expiry</th>
			</tr>
			<?php
			$hid= $_COOKIE['hospitalid'];
			 $result=mysqli_query($connection, "SELECT * FROM hospital_record where h_id='$hid' ORDER BY hr_dateadded DESC LIMIT 20");
				// Loop for displaying each row
			 if($result){
				while($row=mysqli_fetch_assoc( $result )){
					echo '<tr>',
							'<td>', $row['hr_uid'] ,'</td>',
							'<td>', $row['hr_bloodtype'] ,'</td>',
							'<td>', $row['hr_dateadded'] ,'</td>',
							'<td>' , $row['hr_expiry'] , '</td>',
						'</tr>';
				}}
				else
					echo "Error occurred please try again!";
			?>
		</table></center>
		<footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright"><font color="black">Copyright &copy; LIFE-LINE 2017</font></span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-heartbeat"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-heart"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-medkit"></i></a>
                        </li>
						
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="#"><font color="black">Privacy Policy</font></a>
                        </li>
                        <li><a href="#"><font color="black">Terms of Use</font></a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
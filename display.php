<?php
 require_once('connect.php');
  if (!isset($_COOKIE['hospitalid']))
	{ // If cookie is not set redirect to login page
		header("Location: login.php");
	}
   $hid=$_GET['key'];


?>

<html>
	<head><title>Data</title>
	
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
		
		body{
			
			background-image:url('1.png');
		}
		h2{
			color:black;
		}
		</style>
	</head>
	<body>
	
	<?php require_once 'navbar3.php';?><br><br><br>
	<h2>Record Of Blood Stock With It's Expiry Dates.</h2>
		<center><?php
			
			 $result=mysqli_query($connection, "SELECT hd_bloodtype,hd_bloodunits,hd_expiry FROM hospital_database where h_id='$hid' ");
			 if($result){
			echo "<table width=80% height=70%>
				<tr>
					<th >Blood Group</th>
					<th >Quantity</th>
					<th >Nearest Expiry</th>
				</tr>";
			
				
			 
				while($row=mysqli_fetch_assoc( $result )){
					echo '<tr>',
							'<td>', $row['hd_bloodtype'] ,'</td>',
							'<td>', $row['hd_bloodunits'] ,'</td>',
							'<td>' , $row['hd_expiry'] , '</td>',
						'</tr>';
				}
			}
			else{
				echo "No results please try again!";
			}
			?></center>
		</table>
		<footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright"><font color="black">Copyright &copy; LIFE-LINE 2017</font></span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline ">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
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

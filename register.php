
<?php
  //Has to recieve value of city from user and then changes has to be made in this document and find.php
  require('connect.php');

  function geocode($address){
 
    // url encode the address
    $address = urlencode($address);
     
    // google map geocode api url
    $url = "http://maps.google.com/maps/api/geocode/json?address={$address}";
 
    // get the json response
    $resp_json = file_get_contents($url);
     
    // decode the json
    $resp = json_decode($resp_json, true);
 
    // response status will be 'OK', if able to geocode given address 
    if($resp['status']=='OK'){
 
        // get the important data
        $lati = $resp['results'][0]['geometry']['location']['lat'];
        $longi = $resp['results'][0]['geometry']['location']['lng'];
        $formatted_address = $resp['results'][0]['formatted_address'];
         
        // verify if data is complete
        if($lati && $longi && $formatted_address){
         
            // put the data in the array
            $data_arr = array();            
             
            array_push(
                $data_arr, 
                    $lati, 
                    $longi, 
                    $formatted_address
                );
             
            return $data_arr;
             
        }else{
            return false;
        }
         
    }else{
        return false;
    }
}

  if (isset($_POST['hospital_name']) && isset($_POST['password'])&& isset($_POST['cpassword'])&& isset($_POST['contact']) && isset($_POST['email']) && isset($_POST['address']) && isset($_POST['reg_no'])){
      echo "<script> alert('SalimIn'); </script>";

    $email = htmlentities($_POST['email'] );
    $user_name = $email;
    $mobile = htmlentities($_POST['contact'] );
    $hospital = htmlentities($_POST['hospital_name'] );
    $city = "Mumbai";               // $city= htmlentities($_POST['city']);
    $password = htmlentities($_POST['password'] );
    $cpassword = htmlentities($_POST['cpassword'] );
    $add = htmlentities($_POST['address'] );
    $reg_no = htmlentities($_POST['reg_no'] );
    if (!empty($email) && !empty($password) && !empty($cpassword) && !empty($user_name)&& !empty($mobile)&& !empty($hospital)&& !empty($city)&& !empty($add)){
		if($cpassword==$password){
        try{
          $loc = geocode($add);
           $address_found=1;
          }catch(Exception $e){
           $address_found=0;
        }
        if ( $address_found) {
          $query = "INSERT INTO `registration` (user_name, email, mobile, hospital, reg_no, city, password,address,latitude,longitude) VALUES ('$user_name', '$email', '$mobile', '$hospital', '$reg_no','$city', '$password', '$loc[2]','$loc[0]','$loc[1]')";
          $response = mysqli_query($connection, $query);        
          if($response){
            echo "<script> alert('User Created Successfully.'')</script>";
            $results = mysqli_query($connection,"SELECT * FROM `registration` WHERE email='$email' AND Password='$password'");
            $field=mysqli_fetch_assoc( $results );

            /*Commented lines are for creating a table for each user which turns out to be a bad idea.
            $tablename = "`hospital`.`hospital_record_".$field['id']."`";
            $create="CREATE TABLE ".$tablename." ( `hr_id` INT NOT NULL , `hr_bloodtype` VARCHAR(10) NOT NULL , `hr_bloodunits` INT NOT NULL DEFAULT '0' , `hr_expiry` DATE NOT NULL , `h_id` INT NOT NULL , PRIMARY KEY (`hr_id`)) ENGINE = InnoDB";
            echo $results = mysqli_query($connection,$create);
            */
            $results = mysqli_query($connection,"INSERT INTO `hospital_database` (hd_bloodtype, hd_bloodunits,hd_expiry, h_id) VALUES ('A+', '0', '2017-12-31', '".$field['id']."')");
            $results = mysqli_query($connection,"INSERT INTO `hospital_database` (hd_bloodtype, hd_bloodunits,hd_expiry, h_id) VALUES ('A-', '0', '2017-12-31', '".$field['id']."')");
            $results = mysqli_query($connection,"INSERT INTO `hospital_database` (hd_bloodtype, hd_bloodunits,hd_expiry,  h_id) VALUES ('B+', '0', '2017-12-31',  '".$field['id']."')");
            $results = mysqli_query($connection,"INSERT INTO `hospital_database` (hd_bloodtype, hd_bloodunits,hd_expiry, h_id) VALUES ('B-', '0', '2017-12-31', '".$field['id']."')");
            $results = mysqli_query($connection,"INSERT INTO `hospital_database` (hd_bloodtype, hd_bloodunits,hd_expiry, h_id) VALUES ('AB+', '0', '2017-12-31', '".$field['id']."')");
            $results = mysqli_query($connection,"INSERT INTO `hospital_database` (hd_bloodtype, hd_bloodunits,hd_expiry, h_id) VALUES ('AB-', '0', '2017-12-31', '".$field['id']."')");
            $results = mysqli_query($connection,"INSERT INTO `hospital_database` (hd_bloodtype, hd_bloodunits,hd_expiry, h_id) VALUES ('O+', '0', '2017-12-31', '".$field['id']."')");
            $results = mysqli_query($connection,"INSERT INTO `hospital_database`(hd_bloodtype, hd_bloodunits,hd_expiry, h_id) VALUES ('O-', '0', '2017-12-31', '".$field['id']."')");
          }else{
            echo "<script> alert('User Registration Failed')</script>";
          }
        }else{
           echo "<script> alert('Search Failed! Invalid Address, try including city name.')</script>";
          }
    }else{
    	echo "<script> alert(Passwords do not match, please try again!')</script>";
    }
	}else{
      echo "<script> alert('Please enter all details!')</script>";
    }
  }
  else
    echo "<script> alert('SalimOut'); </script>";
?>



<!DOCTYPE html>
<html lang="en">

    <head>

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

        <!-- Top content -->
        <div class="top-content" >
        	
            <div class="inner-bg" align="center">
                <div class="container">
                	<?php require_once 'navbar2.php';?>
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong>LIFE-LINE</strong> Registration </h1>
                            <div class="description">
                            	
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-sm-5">
                        	
                        	<div class="form-box" align-content="center">
	                        	<div class="form-top">
	                        		<div class="form-top-left"><br>
	                        			<h3 align="center">Register to our WebApp !</h3>
										
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-lock"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
								
				                   
									
							            <form role="form" action="register.php" method="post" class="register-form" >
				                    	<div class="form-group">
				                    		<label class="sr-only" for="hospital_name">Hospital Name</label>
				                        	<input type="text" name="hospital_name" placeholder="Hospital..." class="form-hospital form-control" id="form-hospital">
				                        </div>

				                        <div class="form-group">
				                    		<label class="sr-only" for="reg_no">Registration Number</label>
				                        	<input type="text" name="reg_no" placeholder="Registration Number..." class="form-email form-control" id="form-email">
				                        </div>
										
										<div class="form-group">
				                    		<label class="sr-only" for="email">Email</label>
				                        	<input type="text" name="email" placeholder="Email..." class="form-email form-control" id="form-email">
				                        </div>
										
										<div class="form-group">
				                    		<label class="sr-only" for="password">Password</label>
				                        	<input type="password" name="password" placeholder="Password..." class="form-password form-control" id="form-password">
				                        </div>
											<div class="form-group">
				                    		<label class="sr-only" for="cpassword"> Confirm Password</label>
				                        	<input type="password" name="cpassword" placeholder=" Confirm Password..." class="form-cpassword form-control" id="form-cpassword">
				                        </div>
										
										 <div class="form-group">
				                        	<label class="sr-only" for="address">Address</label>
				                        	<input type="text" name="address" placeholder="Address..." class="form-contact form-control" id="form-contact">
				                        </div>

				                         <div class="form-group">
				                        	<label class="sr-only" for="city">City</label>
				                        	<select class="form-control" name="city" value="">
				                        		<option value="" disabled selected>Choose your city</option>              <!-- options for Mumbai and Pune only -->
          										<option value="Mumbai">Mumbai</option>
          									    <option value="Pune">Pune</option>
				                        	</select>
				                        </div>
				                        

										<div class="form-group">
				                        	<label class="sr-only" for="contact">Contact</label>
				                        	<input type="text" name="contact" placeholder="Contact..." class="form-contact form-control" id="form-contact">
				                        </div>
				                       
				                        <button type="submit" class="btn">Sign in!</button>
				                    </form>
			                    </div>
		                    </div> </div>
		                   
		                	
                       
                         
                        <div class="col-sm-1 middle-border"></div>
						<div class="social-login"><br><br>
	                        	<h3>Thank You!</h3>
	                        	<div class="social-login-buttons">
		                        
		                        		<i class="fa fa-heart"></i> 
										<i class="fa fa-heart"></i> 
										<i class="fa fa-heart"></i> 
		                        	</a>
	                        	</div>
	                        </div>
                        <div class="col-sm-1"></div>
  <div class="row">
                     


<!--
					 <div class="col-sm-5">
                        	
                        	<div class="form-box" align-content="center">
	                        	<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Login to our WebApp !</h3>
	                            		<p>Enter username and password to log on:</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-lock"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
								
				                   
									
							            <form role="form" action="" method="post" class="login-form" >
				                    	<div class="form-group">
				                    		<label class="sr-only" for="form-username">Username</label>
				                        	<input type="text" name="form-username" placeholder="Username..." class="form-username form-control" id="form-username">
				                        </div>
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-password">Password</label>
				                        	<input type="password" name="form-password" placeholder="Password..." class="form-password form-control" id="form-password">
				                        </div>
				                        <button type="submit" class="btn">Sign in!</button>
				                    </form>
			                    </div>
		                    </div>
		                            
				  
                        <div class="col-sm-5">
                        	
                        	<div class="form-box">
                        		<div class="form-top">
	                        		<div class="form-top-left">
	                        			<h3>Sign up now</h3>
	                            		<p>Fill in the form below to get instant access:</p>
	                        		</div>
	                        		<div class="form-top-right">
	                        			<i class="fa fa-pencil"></i>
	                        		</div>
	                            </div>
	                            <div class="form-bottom">
				                    <form role="form" action="" method="post" class="registration-form">
				                    	<div class="form-group">
				                    	<!--	<label class="sr-only" for="form-first-name">First name</label>
				                        	<input type="text" name="form-first-name" placeholder="Hospital name..." class="form-first-name form-control" id="form-first-name">
				                        </div>
										<div class="form-group">
										Address
	<TEXTAREA NAME=TXTADDRESS ROWS=9 COLS=43 placeholder="Address Of Your Hospital..."></TEXTAREA><BR>
</div>
				                       
				                        <div class="form-group">
				                        	<label class="sr-only" for="form-email">Email</label>
				                        	<input type="text" name="form-email" placeholder="Email..." class="form-email form-control" id="form-email">
				                        </div>
										 <div class="form-group">
										Select State  <SELECT NAME=CMBSTATE>
				<OPTION VALUE=0 SELECTED>Banglore</OPTION>
					<OPTION VALUE=1>Delhi</OPTION>
					<OPTION VALUE=2>Mumbai</OPTION>
					<OPTION VALUE=3>Ahmedabad</OPTION>
					<OPTION VALUE=4>Chennai</OPTION>
					<OPTION VALUE=5>Kolkatta</OPTION>
			</SELECT><BR></div>
			
			<!--PASTED FROM REGISTER 
			
			<div class="input-field col s12">
			<!--  <label for="hospital_name">Hospital Name</label> 
         <input name="hospital_name" id="hospital_name" type="text" class="validate" placeholder="Hospital Name" padding-bottom="2px" width="100%">
       <br>
        </div>
        <div class="input-field col s12">
		   <!--<label for="registration_number">Registration Number</label> -->
        <!--  <input name="reg_no" id="registration_number" type="text" class="validate" placeholder="Registration Number">
        <br>
        </div> 
        <div class="input-field col s12">
		 <!-- <label for="email">Email</label> 
          <input name="email" id="email" type="email" class="validate" placeholder="Email">
          <br>
        </div>
        <div class="input-field col s12">
		<!-- <label for="password" >Password</label> -->
         <!-- <input name="password" id="password" type="password" class="validate" placeholder="Password">
           <br>
        </div>
         <div class="input-field col s4 ">
          <label for="city">City</label>
         <select name="city">       
            <option value="" disabled selected>Choose your city</option>              <!-- options for Mumbai and Pune only -->
         <!--  <option value="Mumbai">Mumbai</option>
            <option value="Pune">Pune</option>
            <br>
         </select>
        </div>
        <div class="input-field col s4">
		<label>State</label>
            <select>
			 
              <option value="" disabled selected>Choose your state</option>
              <option value="Maharashtra">Maharashtra</option>
            </select> <br>
           
         </div>
        <div class="input-field col s4">
       <!--  <label for="zip">Zip</label> -->
         <!-- <input id="zip" type="text" placeholder="Zip"></textarea>
          <br>
        </div>

				                        
										 <div class="form-group">
										 <input type="checkbox" name="form-email">  I Accept All Terms And Conditions.<br>
				                        <button type="submit" class="btn">Sign me up!</button>
				                    </form>
			                    </div>
                        	</div>
                        	
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div> -->

        <!-- Footer -->
        <footer>
        	<div class="container">
        		<div class="row">
        			
        			<div class="col-sm-8 col-sm-offset-2"><br><br>
        				<div class="footer-border"></div>
        				<p>Made With Full Care And Security <strong>By CODEBROS</strong></a> 
        					 <i class="fa fa-smile-o"></i></p>
        			</div>
        			
        		</div>
        	</div>
        </footer>

        <!-- Javascript -->
        <script src="assets/js/jquery-1.11.1.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/js/jquery.backstretch.min.js"></script>
        <script src="assets/js/scripts.js"></script>
        
        <!--[if lt IE 10]>
            <script src="assets/js/placeholder.js"></script>
        <![endif]-->

    </body>

</html>